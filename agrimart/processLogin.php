<?php

session_start();

$conn = new mysqli(
    "localhost",
    "root",
    "",
    "agrimart_db"
);

$email = $_POST['email'];
$password = $_POST['password'];

$result = $conn->query(

    "SELECT *
FROM users
WHERE email='$email'"

);

if (
    $result->num_rows == 0
) {

    die("User not found");
}

$user =
    $result->fetch_assoc();

if (
    password_verify(
        $password,
        $user['password']
    )
) {

    $_SESSION['id'] = $user['id'];

    $_SESSION['role'] = $user['role'];

    $_SESSION['fullname'] = $user['fullname'];

    if (
        $user['role'] == "admin"
    ) {

        header(
            "location:approveSeller.php"
        );
    } elseif (
        $user['role'] == "seller"
    ) {

        header(
            "location:sellerDashboard.php"
        );
    } else {

        header(
            "location:product.php"
        );
    }
} else {

    die("Wrong Password");
}
