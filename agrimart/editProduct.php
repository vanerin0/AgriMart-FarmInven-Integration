<?php

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

$row=
$product->fetch_assoc();


if(isset($_POST['update'])){

$name=$_POST['product_name'];

$stock=$_POST['stock_level'];

$price=$_POST['price'];

$image=$row['image'];


/* upload new image if selected */

if(
isset($_FILES['image'])
&&
$_FILES['image']['name']!=""

){

$filename=
time().
"_".
$_FILES['image']['name'];

$tmp=
$_FILES['image']['tmp_name'];

move_uploaded_file(

$tmp,

"uploads/".$filename

);

$image=$filename;

}


$conn->query(

"UPDATE products

SET

product_name='$name',

stock_level='$stock',

price='$price',

image='$image'

WHERE id='$id'"

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

<title>Edit Product</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">

<style>

body{

font-family:Poppins;

background:
linear-gradient(
135deg,
#eef7ee,
#f5f7fa
);

padding:50px;

}

.card{

max-width:650px;

margin:auto;

background:white;

padding:30px;

border-radius:25px;

box-shadow:
0 10px 25px rgba(0,0,0,.08);

}

h1{

margin-bottom:20px;

}

input{

width:100%;

padding:15px;

margin-bottom:15px;

border-radius:12px;

border:1px solid #ddd;

}

.preview{

margin-bottom:20px;

text-align:center;

}

.preview img{

width:200px;

height:200px;

object-fit:cover;

border-radius:15px;

border:1px solid #ddd;

}

button{

width:100%;

padding:15px;

background:#2e7d32;

border:none;

color:white;

border-radius:12px;

cursor:pointer;

font-size:16px;

}

button:hover{

opacity:.9;

}

</style>

</head>

<body>

<div class="card">

<h1>

✏️ Edit Product

</h1>

<div class="preview">

<?php

if(
!empty($row['image'])
){

?>

<img
src="uploads/<?php echo $row['image'];?>"
>

<?php

}else{

echo "<h2>🥬</h2>";

}

?>

</div>


<form
method="POST"
enctype="multipart/form-data"
>

<input
name="product_name"
value="<?php echo $row['product_name'];?>"
required
>


<input
type="number"
name="stock_level"
value="<?php echo $row['stock_level'];?>"
required
>


<input
type="number"
step=".01"
name="price"
value="<?php echo $row['price'];?>"
required
>


<input
type="file"
name="image"
>


<button
name="update"
>

Update Product

</button>

</form>

</div>

</body>

</html>