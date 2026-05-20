<?php

session_start();

$id=$_GET['id'];

$action=$_GET['action'];

if(isset($_SESSION['cart'][$id])){

if($action=="plus"){

$_SESSION['cart'][$id]['quantity']++;

}

if($action=="minus"){

if($_SESSION['cart'][$id]['quantity']>1){

$_SESSION['cart'][$id]['quantity']--;

}

}

}

header(
"location:cart.php"
);

?>