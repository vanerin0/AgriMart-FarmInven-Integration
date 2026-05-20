<?php

session_start();

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