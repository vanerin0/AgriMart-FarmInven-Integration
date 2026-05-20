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


$check=$conn->query(

"SELECT *
FROM seller_profiles
WHERE user_id='$user'"

);

if($check->num_rows>0){

header(
"location:sellerDashboard.php"
);

exit();

}


if(isset($_POST['submit'])){

$shop=$_POST['shop_name'];

$contact=$_POST['contact'];

$address=$_POST['address'];

$conn->query(

"INSERT INTO seller_profiles
(
user_id,
shop_name,
contact,
address,
status
)

VALUES
(
'$user',
'$shop',
'$contact',
'$address',
'Pending'
)"

);

echo "

<script>

alert(
'Seller application submitted'
);

window.location='login.php';

</script>

";

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

padding:30px;

border-radius:25px;

}

input,textarea{

width:100%;

padding:15px;

margin-bottom:15px;

border:1px solid #ddd;

border-radius:12px;

}

button{

width:100%;

padding:15px;

border:none;

background:#2e7d32;

color:white;

border-radius:12px;

}

</style>

</head>

<body>

<div class="card">

<h1>

🏪 Seller Application

</h1>

<br>

<form method="POST">

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

<button
name="submit"
>

Submit

</button>

</form>

</div>

</body>
</html>