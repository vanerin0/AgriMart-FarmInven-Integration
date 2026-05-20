<?php

session_start();

$conn=new mysqli(
"localhost",
"root",
"",
"agrimart_db"
);

$fullname=$_POST['fullname'];
$email=$_POST['email'];
$password=$_POST['password'];
$role=$_POST['role'];

$check=$conn->query(

"SELECT *
FROM users
WHERE email='$email'"

);

if(
$check->num_rows>0
){

die(
"Email already exists"
);

}

$hash=
password_hash(
$password,
PASSWORD_DEFAULT
);

$conn->query(

"INSERT INTO users
(
fullname,
email,
password,
role
)

VALUES
(
'$fullname',
'$email',
'$hash',
'$role'
)"

);

$_SESSION['success']=
"Registration Successful";

header(
"location:login.php"
);