<?php
include("checkAuth.php");

if($_SESSION['role']!="seller"){

die("Unauthorized");

}
?>

<!DOCTYPE html>
<html>
<head>

<title>Seller Registration</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">

<style>

body{
font-family:Poppins;
background:#eef7ee;
padding:50px;
}

.card{

max-width:600px;
margin:auto;

background:white;

padding:35px;

border-radius:25px;

box-shadow:
0 10px 25px rgba(0,0,0,.1);

}

input,textarea{

width:100%;
padding:15px;
margin-bottom:15px;

border-radius:12px;

border:1px solid #ddd;

}

button{

width:100%;

padding:15px;

border:none;

border-radius:12px;

background:#2e7d32;

color:white;

}

</style>

</head>

<body>

<div class="card">

<h1>🏪 Seller Information</h1>

<form
action="processSeller.php"
method="POST"
>

<input
name="shop_name"
placeholder="Shop Name"
required
>

<input
name="contact"
placeholder="Contact Number"
required
>

<textarea
name="address"
placeholder="Address"
required
></textarea>

<button>

Submit

</button>

</form>

</div>

</body>
</html>