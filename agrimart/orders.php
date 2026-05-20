<?php

include("checkAuth.php");

$conn=new mysqli(
"localhost",
"root",
"",
"agrimart_db"
);

$email=$_SESSION['email'];

$name=$_SESSION['fullname'];


/* auto create customer if missing */

$customer=$conn->query(

"SELECT *
FROM customers
WHERE email='$email'"

);

if($customer->num_rows==0){

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

$data=
$customer->fetch_assoc();

$customer_id=
$data['customer_id'];

}


$orders=$conn->query(

"SELECT *
FROM orders
WHERE customer_id='$customer_id'
ORDER BY order_id DESC"

);

?>

<!DOCTYPE html>
<html>

<head>

<title>My Orders</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Poppins,sans-serif;
}

body{
background:
linear-gradient(
135deg,
#eef7ee,
#f5f7fa
);
padding:30px;
}

.navbar{

background:white;
padding:20px 30px;

display:flex;
justify-content:space-between;

border-radius:20px;

box-shadow:
0 10px 25px rgba(0,0,0,.08);

margin-bottom:30px;

}

.logo{

font-size:30px;
font-weight:700;
color:#2e7d32;

}

.nav a{

margin-left:20px;
text-decoration:none;
color:#555;

}

.card{

background:white;

padding:25px;

border-radius:25px;

margin-bottom:25px;

box-shadow:
0 10px 25px rgba(0,0,0,.08);

}

.badge{

padding:10px 15px;

border-radius:30px;

font-size:13px;

font-weight:600;

float:right;

}

.pending{
background:#fff3cd;
color:#856404;
}

.completed{
background:#d4edda;
color:#155724;
}

.cancelled{
background:#f8d7da;
color:#721c24;
}

.product{

padding:15px;

margin-top:10px;

background:#f9fafb;

border-radius:15px;

}

.empty{

background:white;

padding:50px;

text-align:center;

border-radius:25px;

}

</style>

</head>

<body>


<div class="navbar">

<div class="logo">

🌱 AgriMart

</div>

<div class="nav">

<a href="product.php">

Products

</a>

<a href="cart.php">

Cart

</a>

<a href="logout.php">

Logout

</a>

</div>

</div>


<h1>

🛒 My Orders

</h1>

<br>


<?php

if($orders->num_rows==0){

?>

<div class="empty">

No Orders Yet

</div>

<?php

}


while(
$order=
$orders->fetch_assoc()
){

$status=
strtolower(
$order['status']
);

?>

<div class="card">

<div
class="badge <?php echo $status;?>"
>

<?php
echo $order['status'];
?>

</div>


<h2>

Order #

<?php
echo $order['order_id'];
?>

</h2>

<br>

Total:

₱

<?php
echo $order['total_amount'];
?>

<br><br>

<h3>

Products

</h3>


<?php

$id=
$order['order_id'];

$items=
$conn->query(

"SELECT

p.product_name,
oi.quantity

FROM order_items oi

JOIN products p

ON oi.product_uuid=
p.product_uuid

WHERE oi.order_id='$id'"

);


while(
$item=
$items->fetch_assoc()
){

?>

<div class="product">

<b>

<?php
echo $item['product_name'];
?>

</b>

<br>

Quantity:

<?php
echo $item['quantity'];
?>

</div>

<?php } ?>

</div>

<?php } ?>

</body>

</html>