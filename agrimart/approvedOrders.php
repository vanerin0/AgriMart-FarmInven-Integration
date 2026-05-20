<?php

include("checkAuth.php");

if($_SESSION['role']!="admin"){

die("Unauthorized");

}

$conn=new mysqli(
"localhost",
"root",
"",
"agrimart_db"
);


/* approve */

if(isset($_GET['approve'])){

$orderId=$_GET['approve'];


/* call node api */

file_get_contents(

"http://localhost:3001/api/orders/complete/".$orderId

);


$conn->query(

"UPDATE orders
SET status='Completed'
WHERE order_id='$orderId'"

);

header(
"location:approvedOrders.php"
);

exit();

}


/* decline */

if(isset($_GET['decline'])){

$orderId=$_GET['decline'];

$conn->query(

"UPDATE orders
SET status='Cancelled'
WHERE order_id='$orderId'"

);

header(
"location:approvedOrders.php"
);

exit();

}


$orders=$conn->query(

"SELECT

o.*,
c.full_name

FROM orders o

JOIN customers c

ON o.customer_id=
c.customer_id

WHERE o.status='Pending'

ORDER BY o.order_id DESC"

);

?>

<!DOCTYPE html>
<html>

<head>

<title>

Approve Orders

</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Poppins;
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

.nav{

background:white;

padding:20px;

border-radius:20px;

margin-bottom:30px;

box-shadow:
0 10px 25px rgba(0,0,0,.08);

display:flex;

justify-content:space-between;

}

.logo{

font-size:30px;

font-weight:700;

color:#2e7d32;

}

.card{

background:white;

padding:25px;

border-radius:25px;

margin-bottom:25px;

box-shadow:
0 10px 25px rgba(0,0,0,.08);

}

.product{

background:#f5f5f5;

padding:12px;

margin-top:10px;

border-radius:12px;

}

.btn{

display:inline-block;

padding:12px 18px;

margin-top:15px;

margin-right:10px;

border-radius:10px;

text-decoration:none;

color:white;

}

.approve{

background:#2e7d32;

}

.decline{

background:#e53935;

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


<div class="nav">

<div class="logo">

🌱 Order Approval

</div>

<a href="adminDashboard.php">

Dashboard

</a>

</div>


<?php

if($orders->num_rows==0){

?>

<div class="empty">

No Pending Orders

</div>

<?php

}


while(
$order=
$orders->fetch_assoc()
){

?>

<div class="card">

<h2>

Order #

<?php
echo $order['order_id'];
?>

</h2>

<br>

Customer:

<b>

<?php
echo $order['full_name'];
?>

</b>

<br><br>

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

$items=$conn->query(

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

<?php

echo
$item['product_name'];

?>



x

<?php

echo
$item['quantity'];

?>

</div>

<?php } ?>


<a
class="btn approve"
href="
approvedOrders.php?approve=
<?php echo $order['order_id'];?>
">

Approve

</a>


<a
class="btn decline"
href="
approvedOrders.php?decline=
<?php echo $order['order_id'];?>
">

Decline

</a>

</div>

<?php } ?>

</body>
</html>