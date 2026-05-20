<?php

include("checkAuth.php");

error_reporting(E_ALL);
ini_set('display_errors',1);

$conn=new mysqli(
"localhost",
"root",
"",
"agrimart_db"
);

if(!isset($_SESSION['cart']) || count($_SESSION['cart'])==0){

die("Cart Empty");

}

$email=$_SESSION['email'];


/* check if customer exists */

$customerQuery=$conn->query(

"SELECT *
FROM customers
WHERE email='$email'"

);


/* auto create customer profile if missing */

if($customerQuery->num_rows==0){

$name=$_SESSION['fullname'];

$conn->query(

"INSERT INTO customers
(
full_name,
email
)

VALUES
(
'$name',
'$email'
)"

);

$customer_id=
$conn->insert_id;

}else{

$customer=
$customerQuery->fetch_assoc();

$customer_id=
$customer['customer_id'];

}


$total=0;

foreach($_SESSION['cart'] as $item){

$total+=
$item['price']
*
$item['quantity'];

}


/* create order */

$conn->query(

"INSERT INTO orders
(
customer_id,
total_amount,
status
)

VALUES
(
'$customer_id',
'$total',
'Pending'
)"

);

$orderId=
$conn->insert_id;


/* save order items */

foreach($_SESSION['cart'] as $item){

$subtotal=

$item['price']
*
$item['quantity'];

$conn->query(

"INSERT INTO order_items
(
order_id,
product_uuid,
quantity,
price,
subtotal
)

VALUES
(
'$orderId',
'".$item['product_uuid']."',
'".$item['quantity']."',
'".$item['price']."',
'$subtotal'
)"

);

}


unset($_SESSION['cart']);

?>

<!DOCTYPE html>

<html>

<head>

<title>

Order Success

</title>

<style>

body{

font-family:Arial;

display:flex;

justify-content:center;

align-items:center;

height:100vh;

background:#eef7ee;

}

.card{

background:white;

padding:50px;

border-radius:20px;

text-align:center;

}

a{

padding:15px 25px;

background:#2e7d32;

color:white;

text-decoration:none;

border-radius:10px;

}

</style>

</head>

<body>

<div class="card">

<h1>

✅ Order Submitted

</h1>

<br>

Order #

<?php echo $orderId; ?>

<br><br>

Waiting for admin approval

<br><br>

<a href="product.php">

Continue Shopping

</a>

</div>

</body>

</html>