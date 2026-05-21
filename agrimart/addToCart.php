<?php

session_start();

include("checkAuth.php");

if(!isset($_SESSION['role']) || $_SESSION['role']!="customer"){

header("location:product.php");

exit();

}

$conn=new mysqli(
"localhost",
"root",
"",
"agrimart_db"
);

if($conn->connect_error){

die("Database Error");

}


$id=$_GET['id'];


$result=$conn->query(

"SELECT *
FROM products
WHERE id='$id'"

);


if($result->num_rows==0){

die("Product not found");

}


$row=$result->fetch_assoc();


if((int)$row['stock_level']<=0){

die("Out of stock");

}


/* create cart session */

if(!isset($_SESSION['cart'])){

$_SESSION['cart']=[];

}


/* check if already exists */

$found=false;

foreach($_SESSION['cart'] as $key=>$item){

if(
$item['product_uuid']==$row['product_uuid']
){

$_SESSION['cart'][$key]['quantity']++;

$found=true;

break;

}

}


/* add new item */

if(!$found){

$_SESSION['cart'][]=[

"product_uuid"=>$row['product_uuid'],

"id"=>$row['id'],

"name"=>$row['product_name'],

"price"=>$row['price'],

"quantity"=>1,

"image"=>$row['image']

];

}


header("location:cart.php");

exit();

?>