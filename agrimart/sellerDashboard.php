<?php

include("checkAuth.php");

if($_SESSION['role']!="seller"){

die("Unauthorized");

}

$conn=new mysqli(
"localhost",
"root",
"",
"agrimart_db"
);

$user=$_SESSION['id'];

$result=$conn->query(

"SELECT *
FROM seller_profiles
WHERE user_id='$user'"

);

$seller=
$result->fetch_assoc();

if(
$seller['status']!="Approved"
){

die("Waiting for admin approval");

}

?>

<!DOCTYPE html>
<html>

<head>

<title>Seller Dashboard</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

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

padding:40px;

}

.card{

max-width:1000px;

margin:auto;

background:white;

padding:40px;

border-radius:25px;

box-shadow:
0 10px 30px rgba(0,0,0,.08);

}

h1{

margin-bottom:30px;

color:#2e7d32;

}

.btn{

display:inline-block;

padding:15px 30px;

background:#2e7d32;

color:white;

text-decoration:none;

border-radius:12px;

margin-right:15px;

}

</style>

</head>

<body>

<div class="card">

<h1>

🏪 Seller Dashboard

</h1>

<p>

Welcome

<b>

<?php
echo $_SESSION['fullname'];
?>

</b>

</p>

<br>

<a
class="btn"
href="addProduct.php"
>

Add Product

</a>

<a
class="btn"
href="manageProducts.php"
>

Manage Products

</a>

<a
class="btn"
href="logout.php"
>

Logout

</a>

</div>

</body>

</html>