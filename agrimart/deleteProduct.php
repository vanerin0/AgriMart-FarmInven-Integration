<?php

include("checkAuth.php");

$conn=new mysqli(
"localhost",
"root",
"",
"agrimart_db"
);

$id=
$_GET['id'];

$conn->query(

"DELETE
FROM products
WHERE id='$id'"

);

header(
"location:manageProducts.php"
);