<?php

session_start();

$conn=new mysqli(
"localhost",
"root",
"",
"agrimart_db"
);

if($conn->connect_error){

die(
"Database Error"
);

}

$email=$_POST['email'];

$password=$_POST['password'];


$result=$conn->query(

"SELECT *
FROM users
WHERE email='$email'"

);


if(
$result->num_rows==0
){

die(

"User not found"

);

}


$user=
$result->fetch_assoc();



if(

password_verify(

$password,

$user['password']

)

){

/* session data */

$_SESSION['id']=$user['id'];

$_SESSION['role']=$user['role'];

$_SESSION['fullname']=$user['fullname'];

$_SESSION['email']=$user['email'];


/* role routing */

if(
$user['role']=="admin"
){

header(

"location:adminDashboard.php"

);

exit();

}


elseif(
$user['role']=="seller"
){

$check=$conn->query(

"SELECT *
FROM seller_profiles
WHERE user_id='".$_SESSION['id']."'
AND status='Approved'"

);


if(
$check->num_rows>0
){

header(
"location:sellerDashboard.php"
);

}else{

header(
"location:sellerRegister.php"
);

}

exit();

}


else{

header(

"location:product.php"

);

exit();

}


}

else{

die(

"Wrong Password"

);

}

?>