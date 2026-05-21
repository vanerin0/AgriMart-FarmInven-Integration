<?php

include("checkAuth.php");

if($_SESSION['role']!="seller"){

die("Unauthorized");

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

$sellerId=$_SESSION['id'];


/* TOTAL PRODUCTS */

$totalProducts=0;

$productQuery=$conn->query(

"SELECT COUNT(*) as total
FROM products
WHERE seller_id='$sellerId'"

);

if($productQuery){

$productData=$productQuery->fetch_assoc();

$totalProducts=$productData['total'];

}


/* DEFAULT VALUES */

$ordersToday=0;

$monthlySales=0;


/* CHECK IF ORDERS TABLE EXISTS */

$checkOrders=$conn->query(

"SHOW COLUMNS FROM orders LIKE 'seller_id'"

);

if($checkOrders && $checkOrders->num_rows>0){


/* ORDERS TODAY */

$orderQuery=$conn->query(

"SELECT COUNT(*) as total
FROM orders
WHERE seller_id='$sellerId'
AND DATE(created_at)=CURDATE()"

);

if($orderQuery){

$orderData=$orderQuery->fetch_assoc();

$ordersToday=$orderData['total'];

}


/* MONTHLY SALES */

$salesQuery=$conn->query(

"SELECT SUM(total_amount) as total
FROM orders
WHERE seller_id='$sellerId'
AND MONTH(created_at)=MONTH(CURDATE())
AND YEAR(created_at)=YEAR(CURDATE())"

);

if($salesQuery){

$salesData=$salesQuery->fetch_assoc();

$monthlySales=$salesData['total'];

if(!$monthlySales){

$monthlySales=0;

}

}

}

?>

<!DOCTYPE html>
<html>

<head>

<title>Seller Dashboard</title>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1"
>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet"
>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
rel="stylesheet"
>

<link
href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
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

min-height:100vh;

}


/* NAVBAR */

.navbar{

background:white;

padding:18px 40px;

display:flex;

justify-content:space-between;
align-items:center;

box-shadow:
0 5px 20px rgba(0,0,0,.08);

}

.logo{

font-size:28px;

font-weight:700;

color:#2e7d32;

}

.nav-links a{

text-decoration:none;

margin-left:15px;

padding:12px 20px;

border-radius:12px;

font-size:14px;

font-weight:600;

transition:.3s;

display:inline-block;

}

.logout{

background:#dc3545;

color:white;

}

.logout:hover{

background:#bb2d3b;

}


/* CONTAINER */

.container-box{

max-width:1200px;

margin:40px auto;

padding:0 20px;

}


/* WELCOME */

.welcome{

background:white;

padding:35px;

border-radius:25px;

box-shadow:
0 10px 30px rgba(0,0,0,.08);

margin-bottom:35px;

}

.welcome h1{

font-size:35px;

color:#2e7d32;

margin-bottom:10px;

}

.welcome p{

color:#666;

font-size:16px;

}


/* STATS */

.stats{

display:grid;

grid-template-columns:
repeat(
auto-fit,
minmax(250px,1fr)
);

gap:25px;

margin-bottom:40px;

}

.stat-card{

background:white;

padding:30px;

border-radius:25px;

box-shadow:
0 10px 25px rgba(0,0,0,.08);

transition:.3s;

}

.stat-card:hover{

transform:
translateY(-6px);

}

.stat-icon{

width:70px;
height:70px;

border-radius:20px;

display:flex;

justify-content:center;
align-items:center;

font-size:32px;

color:white;

margin-bottom:20px;

}

.bg1{

background:
linear-gradient(
135deg,
#43a047,
#2e7d32
);

}

.bg2{

background:
linear-gradient(
135deg,
#ff9800,
#f57c00
);

}

.bg3{

background:
linear-gradient(
135deg,
#1976d2,
#0d47a1
);

}

.stat-card h2{

font-size:38px;

margin-bottom:10px;

color:#222;

}

.stat-card p{

color:#666;

font-size:16px;

}


/* ACTIONS */

.actions{

display:grid;

grid-template-columns:
repeat(
auto-fit,
minmax(220px,1fr)
);

gap:25px;

}

.action-card{

background:white;

padding:35px;

border-radius:25px;

text-decoration:none;

box-shadow:
0 10px 25px rgba(0,0,0,.08);

transition:.3s;

color:#222;

text-align:center;

}

.action-card:hover{

transform:
translateY(-6px);

}

.action-card i{

font-size:45px;

margin-bottom:18px;

color:#2e7d32;

display:block;

}

.action-card h3{

font-size:22px;

margin-bottom:10px;

}

.action-card p{

color:#666;

font-size:14px;

}

</style>

</head>

<body>


<div class="navbar">

<div class="logo">

🌾 AgriMart Seller

</div>

<div class="nav-links">

<a
href="logout.php"
class="logout"
>

Logout

</a>

</div>

</div>


<div class="container-box">


<div class="welcome">

<h1>

Welcome,
<?php echo $_SESSION['fullname']; ?> 👋

</h1>

<p>

Manage your farm products, monitor orders, and grow your sales easily.

</p>

</div>



<div class="stats">


<div class="stat-card">

<div class="stat-icon bg1">

<i class="bi bi-box-seam"></i>

</div>

<h2>

<?php echo $totalProducts; ?>

</h2>

<p>

Total Products

</p>

</div>



<div class="stat-card">

<div class="stat-icon bg2">

<i class="bi bi-cart-check"></i>

</div>

<h2>

<?php echo $ordersToday; ?>

</h2>

<p>

Orders Today

</p>

</div>



<div class="stat-card">

<div class="stat-icon bg3">

<i class="bi bi-currency-dollar"></i>

</div>

<h2>

₱<?php echo number_format($monthlySales,2); ?>

</h2>

<p>

Monthly Sales

</p>

</div>


</div>



<div class="actions">


<a
href="addProduct.php"
class="action-card"
>

<i class="bi bi-plus-circle"></i>

<h3>

Add Product

</h3>

<p>

Upload new farm products

</p>

</a>



<a
href="manageProducts.php"
class="action-card"
>

<i class="bi bi-basket"></i>

<h3>

Manage Products

</h3>

<p>

Update and manage inventory

</p>

</a>



<a
href="orders.php"
class="action-card"
>

<i class="bi bi-cart4"></i>

<h3>

Orders

</h3>

<p>

View customer orders

</p>

</a>



<a
href="product.php"
class="action-card"
>

<i class="bi bi-shop"></i>

<h3>

Marketplace

</h3>

<p>

Browse AgriMart products

</p>

</a>


</div>

</div>

</body>

</html>