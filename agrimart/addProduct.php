<?php

include("checkAuth.php");

$conn=new mysqli(
"localhost",
"root",
"",
"agrimart_db"
);

if(
isset($_POST['save'])
){

$name=
$_POST['product_name'];

$stock=
$_POST['stock_level'];

$price=
$_POST['price'];

$uuid=
uniqid();

$sku=
"SKU".rand(
1000,
9999
);

$seller=
$_SESSION['id'];

$conn->query(

"INSERT INTO products
(
product_uuid,
sku_code,
product_name,
stock_level,
price,
seller_id
)

VALUES
(
'$uuid',
'$sku',
'$name',
'$stock',
'$price',
'$seller'
)"

);

header(
"location:manageProducts.php"
);

}

?>

<!DOCTYPE html>

<html>

<head>

<title>

Add Product

</title>

<style>

body{

font-family:Poppins;

padding:50px;

background:#eef7ee;

}

.card{

max-width:600px;

margin:auto;

background:white;

padding:30px;

border-radius:25px;

}

input{

width:100%;

padding:15px;

margin-bottom:15px;

}

button{

padding:15px;

width:100%;

background:#2e7d32;

color:white;

border:none;

}

</style>

</head>

<body>

<div class="card">

<h1>

Add Product

</h1>

<form method="POST">

<input
name="product_name"
placeholder="Product Name"
required
>

<input
type="number"
name="stock_level"
placeholder="Stock"
required
>

<input
type="number"
step=".01"
name="price"
placeholder="Price"
required
>

<button
name="save"
>

Save Product

</button>

</form>

</div>

</body>

</html>