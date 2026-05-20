<?php

include("checkAuth.php");

$conn=new mysqli(
"localhost",
"root",
"",
"agrimart_db"
);

$id=
$_SESSION['id'];

$result=
$conn->query(

"SELECT *
FROM products
WHERE seller_id='$id'"

);

?>

<!DOCTYPE html>

<html>

<head>

<title>

Manage Products

</title>

<style>

body{

font-family:Poppins;

background:#f5f7fa;

padding:40px;

}

.card{

background:white;

padding:25px;

border-radius:20px;

margin-bottom:20px;

}

.btn{

padding:10px 20px;

background:red;

color:white;

text-decoration:none;

border-radius:10px;

}

.edit{

background:#2196f3;

margin-right:10px;

}

</style>

</head>

<body>

<h1>

📦 My Products

</h1>

<br>

<?php

while(
$row=
$result->fetch_assoc()
){

?>

<div class="card">

<h2>

<?php
echo $row['product_name'];
?>

</h2>

SKU:

<?php
echo $row['sku_code'];
?>

<br><br>

Stock:

<?php
echo $row['stock_level'];
?>

<br><br>

Price:

₱

<?php
echo $row['price'];
?>

<br><br>

<a
class="btn edit"
href="editProduct.php?id=<?php echo $row['id']; ?>"
>

Edit

</a>

<a
class="btn"
href="
deleteProduct.php?id=
<?php
echo $row['id'];
?>
">

Delete

</a>

</div>

<?php } ?>

</body>

</html>