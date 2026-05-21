<?php

session_start();

include("checkAuth.php");
if(!isset($_SESSION['role']) || $_SESSION['role'] !== "customer"){
    header("location:product.php");
    exit();
}
if(!isset($_SESSION['cart'])){

$_SESSION['cart']=[];

}

$total=0;

?>

<!DOCTYPE html>
<html>

<head>

<title>My Cart</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">

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

}

.navbar{

background:white;

padding:20px 30px;

border-radius:25px;

display:flex;

justify-content:space-between;

margin-bottom:30px;

box-shadow:
0 10px 25px rgba(0,0,0,.08);

}

.logo{

font-size:28px;
font-weight:700;
color:#2e7d32;

}

.container{

max-width:1000px;
margin:auto;

}

.card{

background:white;

padding:20px;

border-radius:25px;

margin-bottom:20px;

display:flex;

gap:20px;

align-items:center;

box-shadow:
0 10px 25px rgba(0,0,0,.08);

}

.image{

width:120px;
height:120px;

border-radius:20px;

overflow:hidden;

background:#f1f1f1;

}

.image img{

width:100%;
height:100%;

object-fit:cover;

}

.placeholder{

display:flex;
justify-content:center;
align-items:center;

height:100%;

font-size:50px;

}

.info{

flex:1;

}

.qtyBox{

display:flex;

align-items:center;

gap:15px;

margin-top:15px;

}

.qtyBtn{

width:35px;
height:35px;

border-radius:50%;

background:#2e7d32;

display:flex;

justify-content:center;
align-items:center;

text-decoration:none;

color:white;

font-size:20px;

font-weight:bold;

}

.remove{

background:#e53935;

padding:10px 15px;

border-radius:10px;

color:white;

text-decoration:none;

}

.bottom{

background:white;

padding:30px;

border-radius:25px;

box-shadow:
0 10px 25px rgba(0,0,0,.08);

}

.btn{

display:inline-block;

padding:15px 25px;

background:#2e7d32;

color:white;

border-radius:12px;

text-decoration:none;

margin-right:15px;

margin-top:15px;

}

.empty{

background:white;

padding:60px;

border-radius:25px;

text-align:center;

}

</style>

</head>

<body>

<div class="navbar">

<div class="logo">

🛒 AgriMart Cart

</div>

</div>

<div class="container">

<?php

if(count($_SESSION['cart'])==0){

?>

<div class="empty">

<h2>

Cart is empty

</h2>

<br>

<a
class="btn"
href="product.php"
>

Continue Shopping

</a>

</div>

<?php

}else{

foreach(
$_SESSION['cart']
as $index=>$item
){

$subtotal=

$item['price']
*
$item['quantity'];

$total+=$subtotal;

?>

<div class="card">

<div class="image">

<?php

if(!empty($item['image'])){

?>

<img
src="uploads/<?php echo $item['image'];?>"
>

<?php

}else{

?>

<div class="placeholder">

🥬

</div>

<?php } ?>

</div>


<div class="info">

<h2>

<?php echo $item['name'];?>

</h2>

<br>

Price:

₱<?php echo $item['price'];?>

<br><br>

<div class="qtyBox">

<a
class="qtyBtn"
href="updateCart.php?id=<?php echo $index;?>&action=minus"
>

−

</a>

<b>

<?php echo $item['quantity'];?>

</b>

<a
class="qtyBtn"
href="updateCart.php?id=<?php echo $index;?>&action=plus"
>

+

</a>

</div>

<br>

Subtotal:

₱<?php echo $subtotal;?>

</div>


<a
class="remove"
href="removeCart.php?id=<?php echo $index;?>"
>

Remove

</a>

</div>

<?php } ?>

<div class="bottom">

<h2>

Grand Total:
₱<?php echo $total;?>

</h2>

<br>

<a
class="btn"
href="product.php"
>

Continue Shopping

</a>

<a
class="btn"
href="placeOrder.php"
>

Place Order

</a>

</div>

<?php } ?>

</div>

</body>
</html>