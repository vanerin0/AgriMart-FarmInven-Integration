<?php

include("checkAuth.php");

$conn=new mysqli(
"localhost",
"root",
"",
"agrimart_db"
);

$search="";

if(isset($_GET['search'])){

$search=
$conn->real_escape_string(
$_GET['search']
);

$result=$conn->query(

"SELECT *
FROM products
WHERE product_name
LIKE '%$search%'"

);

}else{

$result=$conn->query(

"SELECT *
FROM products"

);

}

?>

<!DOCTYPE html>
<html>

<head>

<title>AgriMart Products</title>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width,initial-scale=1"
>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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

padding:30px;

min-height:100vh;

}


/* NAVBAR */

.navbar{

background:white;

padding:18px 35px;

border-radius:25px;

display:flex;

justify-content:space-between;
align-items:center;

box-shadow:
0 10px 30px rgba(0,0,0,.08);

margin-bottom:35px;

}

.logo{

font-size:28px;
font-weight:700;
color:#2e7d32;

}

.links a{

text-decoration:none;

margin-left:25px;

color:#555;

font-weight:500;

transition:.3s;

}

.links a:hover{

color:#2e7d32;

}


/* HEADER */

.header{

margin-bottom:30px;

}

.header h1{

font-size:40px;

color:#222;

}

.header p{

color:#666;

}


/* SEARCH */

.search-box{

display:flex;

justify-content:center;

margin-bottom:35px;

}

.search-box form{

display:flex;

background:white;

padding:10px;

border-radius:50px;

width:700px;

box-shadow:
0 10px 25px rgba(0,0,0,.08);

}

.search-box input{

flex:1;

padding:15px;

border:none;

outline:none;

font-size:15px;

border-radius:50px;

}

.search-box button{

width:150px;

border:none;

background:
linear-gradient(
135deg,
#43a047,
#2e7d32
);

color:white;

border-radius:50px;

cursor:pointer;

}


/* PRODUCTS */

.products{

display:grid;

grid-template-columns:
repeat(
auto-fit,
minmax(280px,1fr)
);

gap:25px;

}


.card{

background:white;

border-radius:25px;

padding:20px;

box-shadow:
0 10px 25px rgba(0,0,0,.08);

transition:.3s;

overflow:hidden;

}

.card:hover{

transform:
translateY(-8px);

}

.image{

height:200px;

background:#f1f1f1;

border-radius:20px;

overflow:hidden;

margin-bottom:20px;

}

.image img{

width:100%;
height:100%;

object-fit:cover;

}

.placeholder{

height:100%;

display:flex;

justify-content:center;
align-items:center;

font-size:70px;

}


.card h3{

margin-bottom:12px;

color:#222;

}

.info{

margin-bottom:10px;

color:#666;

}

.price{

font-size:22px;

font-weight:700;

color:#2e7d32;

margin-top:10px;

}

.stock{

margin-top:10px;

font-weight:600;

color:#ff9800;

}

button{

width:100%;

padding:14px;

margin-top:20px;

border:none;

border-radius:12px;

background:
linear-gradient(
135deg,
#43a047,
#2e7d32
);

color:white;

font-size:15px;

font-weight:600;

cursor:pointer;

}

button:hover{

opacity:.9;

}

.empty{

background:white;

padding:50px;

border-radius:25px;

grid-column:1/-1;

text-align:center;

}

</style>

</head>

<body>


<div class="navbar">

<div class="logo">

🌱 AgriMart

</div>

<div class="links">

<a href="product.php">

Products

</a>

<?php if(isset($_SESSION['role']) && $_SESSION['role'] === "customer"){ ?>

<a href="cart.php">

Cart

</a>

<a href="orders.php">

Orders

</a>

<?php } ?>

<?php if(isset($_SESSION['role']) && $_SESSION['role'] === "seller"){ ?>

<a href="sellerDashboard.php">

Seller

</a>

<?php } ?>

<a href="logout.php">

Logout

</a>

</div>

</div>



<div class="header">

<h1>

Fresh Farm Products

</h1>

<p>

Buy directly from local farmers

</p>

</div>



<div class="search-box">

<form method="GET">

<input

type="text"

name="search"

placeholder="Search product..."

value="<?php echo $search;?>"

>

<button>

Search 🔍

</button>

</form>

</div>


<div class="products">


<?php

if(
$result->num_rows==0
){

?>

<div class="empty">

<h2>

No products found 🔍

</h2>

</div>

<?php

}


while(
$row=
$result->fetch_assoc()
){

?>

<div class="card">


<div class="image">

<?php

if(
!empty($row['image'])
){

?>

<img
src="uploads/<?php echo $row['image']; ?>"
>

<?php

}else{

?>

<div class="placeholder">

🥬

</div>

<?php

}

?>

</div>


<h3>

<?php
echo $row['product_name'];
?>

</h3>

<div class="info">

SKU:

<?php
echo $row['sku_code'];
?>

</div>


<div class="price">

₱

<?php
echo $row['price'];
?>

</div>


<div class="stock">

Stock:

<?php
if((int)$row['stock_level']>0){
	echo $row['stock_level'];
}else{
	echo "Out of Stock";
}
?>

</div>


<?php if((int)$row['stock_level']>0){ ?>

<?php if(isset($_SESSION['role']) && $_SESSION['role'] === "customer"){ ?>

<a href="addToCart.php?id=<?php echo $row['id'];?>">

<button>

Add To Cart 🛒

</button>

</a>

<?php } else { ?>

<button disabled style="opacity:.7;cursor:not-allowed;">
Customers only
</button>

<?php } ?>

<?php } else { ?>

<button disabled style="opacity:.7;cursor:not-allowed;">
Out of Stock
</button>

<?php } ?>


</div>

<?php } ?>

</div>

</body>
</html>