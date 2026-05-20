<?php

include("checkAuth.php");

if($_SESSION['role']!="customer"){

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
FROM customers
WHERE user_id='$user'"

);

if($check->num_rows>0){

    header("location:product.php");

    exit();

}

if(isset($_POST['submit'])){

    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $fullname = $_SESSION['fullname'];
    $email = $_SESSION['email'];

    $conn->query(

        "INSERT INTO customers
        (
            user_id,
            full_name,
            email,
            phone,
            address
        )

        VALUES
        (
            '$user',
            '$fullname',
            '$email',
            '$phone',
            '$address'
        )"

    );

    echo "<script>alert('Profile saved'); window.location='product.php';</script>";

}

?>

<!DOCTYPE html>
<html>
<head>
<title>Customer Profile</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">
<style>
body{font-family:Poppins; background:#eef7ee; padding:50px}
.card{max-width:600px;margin:auto;background:white;padding:30px;border-radius:25px}
input,textarea{width:100%;padding:15px;margin-bottom:15px;border:1px solid #ddd;border-radius:12px}
button{width:100%;padding:15px;border:none;background:#2e7d32;color:white;border-radius:12px}
</style>
</head>
<body>
<div class="card">
<h1>👤 Customer Profile</h1>
<form method="POST">
<input name="phone" placeholder="Contact Number" required>
<textarea name="address" placeholder="Address" required></textarea>
<button name="submit">Save Profile</button>
</form>
</div>
</body>
</html>
