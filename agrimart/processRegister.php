<?php

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

$fullname=$_POST['fullname'];

$email=$_POST['email'];

$password=password_hash(
$_POST['password'],
PASSWORD_DEFAULT
);

$role=$_POST['role'];


/* CHECK IF EMAIL EXISTS */

$check=$conn->query(

"SELECT *
FROM users
WHERE email='$email'"

);

if($check->num_rows>0){

die(

"
<h2 style='font-family:Arial'>
Email already exists
</h2>

<a href='register.php'>
Back
</a>
"

);

}


/* INSERT USER */

$conn->query(

"INSERT INTO users(

fullname,
email,
password,
role

)

VALUES(

'$fullname',
'$email',
'$password',
'$role'

)"

);


/* GET USER ID */

$userId=$conn->insert_id;


/* AUTO CREATE SELLER PROFILE */

if($role=="seller"){

$conn->query(

"INSERT INTO seller_profiles(

user_id,
shop_name,
address,
status

)

VALUES(

'$userId',
'$fullname Shop',
'Not Set',
'Approved'

)"

);

}


/* REDIRECT */

header(
"location:login.php"
);

?>