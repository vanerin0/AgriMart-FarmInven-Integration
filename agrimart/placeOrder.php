<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("checkAuth.php");

/* DATABASE */

$conn = new mysqli(
	"localhost",
	"root",
	"",
	"agrimart_db"
);

if ($conn->connect_error) {

	die("Database Failed");
}


/* CHECK CART */

if (
	!isset($_SESSION['cart']) ||
	count($_SESSION['cart']) == 0
) {

	die("Cart is empty");
}


/* USER */

$email = $_SESSION['email'];

$name = $_SESSION['fullname'];


/* FIND CUSTOMER */

$customerQuery = $conn->query(

	"SELECT *
FROM customers
WHERE customer_email='$email'"

);


/* CREATE CUSTOMER */

if ($customerQuery->num_rows > 0) {

	$customer = $customerQuery->fetch_assoc();

	$customer_id = $customer['customer_id'];
} else {

	$conn->query(

		"INSERT INTO customers
    (
    full_name,
    customer_email
    )

    VALUES
    (
    '$name',
    '$email'
    )"

	);

	$customer_id = $conn->insert_id;
}


/* TOTAL */

$total = 0;

foreach ($_SESSION['cart'] as $item) {

	$subtotal =
		$item['price']
		*
		$item['quantity'];

	$total += $subtotal;
}


/* CREATE ORDER */

$conn->query(

	"INSERT INTO orders
(
customer_id,
total_amount,
status
)

VALUES
(
'$customer_id',
'$total',
'Pending'
)"

);

$order_id = $conn->insert_id;


/* SAVE ITEMS */

foreach($_SESSION['cart'] as $item){

$product_uuid=$item['product_uuid'];

$qty=$item['quantity'];

$conn->query(

"INSERT INTO order_items
(
order_id,
product_uuid,
quantity
)

VALUES
(
'$order_id',
'$product_uuid',
'$qty'
)"

);


/* deduct stock */

$conn->query(

"UPDATE products
SET stock_level=
stock_level-'$qty'
WHERE product_uuid='$product_uuid'
AND stock_level>='$qty'"

);

}

/* CLEAR CART */

unset($_SESSION['cart']);


/* SUCCESS */

header("location:orders.php");

exit();
