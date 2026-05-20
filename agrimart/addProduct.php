<?php

include("checkAuth.php");

$conn=new mysqli(
"localhost",
"root",
"",
"agrimart_db"
);

if(isset($_POST['save'])){

$name=$_POST['product_name'];

$stock=$_POST['stock_level'];

$price=$_POST['price'];

$uuid=uniqid();

$sku="SKU".rand(
1000,
9999
);

$seller=$_SESSION['id'];

$imageName="";


if(
isset($_FILES['image'])
){

if(
$_FILES['image']['error']==0
){

$imageName=

time().
"_".
basename(
$_FILES['image']['name']
);


$target=

__DIR__.
"/uploads/".
$imageName;



if(

move_uploaded_file(

$_FILES['image']['tmp_name'],

$target

)

){

echo "";

}else{

$error = "Failed uploading image to: " . $target . 
"\nTemp file: " . $_FILES['image']['tmp_name'] . 
"\nFile size: " . $_FILES['image']['size'];

error_log($error);

die($error);

}

}else{

die(

"Image Error: ".
$_FILES['image']['error']

);

}

}


$conn->query(

"INSERT INTO products
(
product_uuid,
sku_code,
product_name,
stock_level,
price,
seller_id,
image
)

VALUES
(
'$uuid',
'$sku',
'$name',
'$stock',
'$price',
'$seller',
'$imageName'
)"

);


header(
"location:manageProducts.php"
);

exit();

}

?>


<!DOCTYPE html>

<html>

<head>

<title>

Add Product

</title>

<link
href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap"
rel="stylesheet"
>

<style>

*{

margin:0;
padding:0;
box-sizing:border-box;
font-family:Poppins,sans-serif;

}

body{

background:

linear-gradient(
135deg,
#eef7ee,
#f5f7fa
);

padding:50px;

min-height:100vh;

}

.card{

max-width:650px;

margin:auto;

background:white;

padding:35px;

border-radius:25px;

box-shadow:
0 10px 30px rgba(0,0,0,.08);

}

h1{

text-align:center;

margin-bottom:25px;

color:#2e7d32;

}

input{

width:100%;

padding:15px;

margin-bottom:15px;

border:1px solid #ddd;

border-radius:12px;

outline:none;

}

.file{

padding:12px;

background:#f5f5f5;

}

button{

width:100%;

padding:15px;

border:none;

border-radius:12px;

background:
linear-gradient(
135deg,
#43a047,
#2e7d32
);

color:white;

font-size:16px;

font-weight:600;

cursor:pointer;

transition:.3s;

}

button:hover{

transform:
translateY(-3px);

}

.back{

display:block;

text-align:center;

margin-top:20px;

text-decoration:none;

color:#666;

}

</style>

</head>

<body>

<div class="card">

<h1>

📦 Add Product

</h1>

<form
method="POST"
enctype="multipart/form-data"
>

<input
name="product_name"
placeholder="Product Name"
required
>

<input
type="number"
name="stock_level"
placeholder="Stock Level"
required
>

<input
type="number"
step=".01"
name="price"
placeholder="Price"
required
>

<input
class="file"
type="file"
name="image"
accept="image/*"
required
>

<button
name="save"
>

Save Product

</button>

</form>

<a
class="back"
href="sellerDashboard.php"
>

← Back

</a>

</div>

</body>

</html>