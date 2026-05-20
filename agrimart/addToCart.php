<?php

session_start();

include("checkAuth.php");

$conn=new mysqli(
"localhost",
"root",
"",
"agrimart_db"
);

$id=$_GET['id'];

$product=$conn->query(

"SELECT *
FROM products
WHERE id='$id'"

);

if(
$product->num_rows==0
){

die("Product not found");

}

$row=
$product->fetch_assoc();


if(
!isset($_SESSION['cart'])
){

$_SESSION['cart']=[];

}


$found=false;


foreach(
$_SESSION['cart']
as &$item
){

if(
$item['id']==$id
){

$item['quantity']++;

$found=true;

break;

}

}


if(!$found){

$_SESSION['cart'][]=[

"id"=>$row['id'],

"product_uuid"=>$row['product_uuid'],

"name"=>$row['product_name'],

"price"=>$row['price'],

"stock"=>$row['stock_level'],

"image"=>$row['image'],

"quantity"=>1

];

}


header(
"location:cart.php"
);

exit();

?>