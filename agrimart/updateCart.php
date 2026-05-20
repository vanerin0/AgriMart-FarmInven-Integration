<?php

session_start();

$id=$_GET['id'];

$action=$_GET['action'];

if(isset($_SESSION['cart'][$id])){

	if($action=="plus"){

		// check current stock before incrementing
		$product_uuid = $_SESSION['cart'][$id]['product_uuid'];
		$conn = new mysqli("localhost","root","","agrimart_db");
		$pq = $conn->query("SELECT stock_level FROM products WHERE product_uuid='".$product_uuid."'");
		if($pq && $pq->num_rows>0){
			$prow = $pq->fetch_assoc();
			if($_SESSION['cart'][$id]['quantity'] < (int)$prow['stock_level']){
				$_SESSION['cart'][$id]['quantity']++;
			}
		}

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