<?php

session_start();

$conn=new mysqli(
"localhost",
"root",
"",
"agrimart_db"
);

if($conn->connect_error){

die("Database Error");

}


/* FORM DATA */

$email=$_POST['email'];

$password=$_POST['password'];


/* FIND USER */

$result=$conn->query(

"SELECT *
FROM users
WHERE email='$email'"

);


/* USER NOT FOUND */

if($result->num_rows==0){

die(

"
<h2 style='font-family:Arial'>
User not found
</h2>

<a href='login.php'>
Back
</a>
"

);

}


$user=$result->fetch_assoc();


/* VERIFY PASSWORD */

if(

password_verify(
$password,
$user['password']
)

){

/* SESSION */

$_SESSION['id']=$user['id'];

$_SESSION['fullname']=$user['fullname'];

$_SESSION['email']=$user['email'];

$_SESSION['role']=$user['role'];


/* ADMIN */

if($user['role']=="admin"){

header(
"location:adminDashboard.php"
);

exit();

}


/* SELLER */

elseif($user['role']=="seller"){

header(
"location:sellerDashboard.php"
);

exit();

}


/* CUSTOMER */

else{

header(
"location:product.php"
);

exit();

}

}


/* WRONG PASSWORD */

else{

die(

"
<h2 style='font-family:Arial'>
Wrong Password
</h2>

<a href='login.php'>
Back
</a>
"

);

}

?>