<?php

include("checkAuth.php");

if(!isset($_SESSION['role']) || $_SESSION['role'] !== "customer"){
    header("location:product.php");
    exit();
}

$id=$_GET['id'];

unset(
$_SESSION['cart'][$id]
);

$_SESSION['cart']=
array_values(
$_SESSION['cart']
);

header(
"location:cart.php"
);

?>