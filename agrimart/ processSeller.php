<?php

include("checkAuth.php");

$conn=new mysqli(
"localhost",
"root",
"",
"agrimart_db"
);

$user=$_SESSION['id'];

$shop=$_POST['shop_name'];

$contact=$_POST['contact'];

$address=$_POST['address'];

$conn->query(

"INSERT INTO seller_profiles
(
user_id,
shop_name,
contact,
address
)

VALUES
(
'$user',
'$shop',
'$contact',
'$address'
)"

);

echo"

<h2>
Application Submitted
</h2>

Pending Admin Approval

<br><br>

<a href='product.php'>

Back

</a>

";