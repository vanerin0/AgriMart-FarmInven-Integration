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


if(isset($_GET['id'])){

$id=$_GET['id'];

$response=
file_get_contents(
"http://localhost:3001/api/orders/complete/".$id
);

$conn->query(

"UPDATE orders
SET status='Completed'
WHERE order_id='$id'"

);

header(
"location:approveOrder.php"
);

}


$orders=$conn->query(

"SELECT *
FROM orders
WHERE status='Pending'
ORDER BY order_id DESC"

);

?>

<!DOCTYPE html>
<html>

<head>

<title>Approve Orders</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">

<style>

body{

font-family:Poppins;

background:#f5f7fa;

padding:40px;

}

.card{

background:white;

padding:25px;

border-radius:20px;

margin-bottom:20px;

box-shadow:
0 10px 25px rgba(0,0,0,.08);

}

.btn{

background:#2e7d32;

padding:12px 20px;

color:white;

text-decoration:none;

border-radius:10px;

}

</style>

</head>

<body>

<h1>

📦 Pending Orders

</h1>

<br>

<?php

if(
$orders->num_rows==0
){

echo"

<h2>
No Pending Orders
</h2>

";

}

while(
$row=
$orders->fetch_assoc()
){

?>

<div class="card">

<h2>

Order #

<?php
echo $row['order_id'];
?>

</h2>

Total:

₱

<?php
echo $row['total_amount'];
?>

<br><br>

<a
class="btn"
href="?id=<?php echo $row['order_id'];?>"
>

Approve

</a>

</div>

<?php } ?>

</body>

</html>