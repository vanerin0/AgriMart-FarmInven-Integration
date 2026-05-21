<?php

include("checkAuth.php");

$conn = new mysqli(
    "localhost",
    "root",
    "",
    "agrimart_db"
);

if ($conn->connect_error) {
    die("Database Error");
}

$id = $_SESSION['id'];

$result = $conn->query(

    "SELECT *
FROM products
WHERE seller_id='$id'
ORDER BY id DESC"

);

$totalProducts = $result->num_rows;

?>

<!DOCTYPE html>
<html>

<head>

    <title>Manage Products</title>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1">

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Poppins, sans-serif;
        }

        body {

            background:
                linear-gradient(135deg,
                    #eef7ee,
                    #f5f7fa);

            padding: 30px;

            min-height: 100vh;

        }


        /* NAVBAR */

        .navbar {

            background: white;

            padding: 20px 30px;

            border-radius: 25px;

            display: flex;

            justify-content: space-between;

            align-items: center;

            flex-wrap: wrap;

            gap: 15px;

            box-shadow:
                0 10px 30px rgba(0, 0, 0, .08);

            margin-bottom: 35px;

        }

        .logo {

            font-size: 30px;

            font-weight: 700;

            color: #2e7d32;

            display: flex;

            align-items: center;

            gap: 10px;

        }

        .nav-links {

            display: flex;

            gap: 15px;

            flex-wrap: wrap;

        }

        .nav-links a {

            text-decoration: none;

            padding: 12px 18px;

            border-radius: 12px;

            font-weight: 500;

            transition: .3s;

            color: #555;

        }

        .nav-links a:hover {

            background: #f3f7f3;

            color: #2e7d32;

        }


        /* HERO */

        .hero {

            background:
                linear-gradient(135deg,
                    #43a047,
                    #2e7d32);

            padding: 45px;

            border-radius: 30px;

            color: white;

            display: flex;

            justify-content: space-between;

            align-items: center;

            flex-wrap: wrap;

            gap: 20px;

            margin-bottom: 35px;

            box-shadow:
                0 15px 35px rgba(46, 125, 50, .25);

        }

        .hero h1 {

            font-size: 42px;

            margin-bottom: 10px;

        }

        .hero p {

            opacity: .9;

            font-size: 16px;

        }

        .hero-badge {

            background: rgba(255, 255, 255, .15);

            padding: 18px 25px;

            border-radius: 20px;

            backdrop-filter: blur(10px);

            font-size: 18px;

            font-weight: 600;

        }


        /* PRODUCTS */

        .products {

            display: grid;

            grid-template-columns:
                repeat(auto-fit,
                    minmax(320px, 1fr));

            gap: 30px;

        }

        .card {

            background: white;

            border-radius: 28px;

            overflow: hidden;

            box-shadow:
                0 12px 30px rgba(0, 0, 0, .08);

            transition: .3s;

        }

        .card:hover {

            transform:
                translateY(-8px);

        }

        .image {

            height: 240px;

            background: #f1f1f1;

            overflow: hidden;

            position: relative;

        }

        .image img {

            width: 100%;

            height: 100%;

            object-fit: cover;

            transition: .4s;

        }

        .card:hover img {

            transform: scale(1.05);

        }

        .placeholder {

            height: 100%;

            display: flex;

            justify-content: center;

            align-items: center;

            font-size: 80px;

        }

        .content {

            padding: 25px;

        }

        .title {

            font-size: 24px;

            font-weight: 700;

            color: #222;

            margin-bottom: 12px;

        }

        .sku {

            font-size: 14px;

            color: #777;

            margin-bottom: 15px;

        }

        .price {

            font-size: 28px;

            font-weight: 700;

            color: #2e7d32;

            margin-bottom: 15px;

        }

        .stock {

            display: inline-block;

            padding: 10px 15px;

            border-radius: 30px;

            font-size: 14px;

            font-weight: 600;

            margin-bottom: 20px;

        }

        .in-stock {

            background: #e8f5e9;

            color: #2e7d32;

        }

        .low-stock {

            background: #fff3cd;

            color: #856404;

        }

        .out-stock {

            background: #ffebee;

            color: #c62828;

        }

        .actions {

            display: flex;

            gap: 12px;

            margin-top: 20px;

        }

        .btn {

            flex: 1;

            padding: 14px;

            border: none;

            border-radius: 15px;

            text-decoration: none;

            text-align: center;

            font-weight: 600;

            transition: .3s;

        }

        .edit-btn {

            background:
                linear-gradient(135deg,
                    #2196f3,
                    #1565c0);

            color: white;

        }

        .delete-btn {

            background:
                linear-gradient(135deg,
                    #ef5350,
                    #c62828);

            color: white;

        }

        .btn:hover {

            transform:
                translateY(-3px);

        }


        /* EMPTY */

        .empty {

            background: white;

            padding: 70px;

            border-radius: 30px;

            text-align: center;

            box-shadow:
                0 10px 25px rgba(0, 0, 0, .08);

        }

        .empty h2 {

            font-size: 32px;

            margin-bottom: 15px;

            color: #444;

        }

        .empty p {

            color: #777;

            margin-bottom: 25px;

        }

        .add-btn {

            display: inline-block;

            padding: 15px 25px;

            border-radius: 15px;

            background:
                linear-gradient(135deg,
                    #43a047,
                    #2e7d32);

            color: white;

            text-decoration: none;

            font-weight: 600;

        }


        /* MOBILE */

        @media(max-width:768px) {

            .hero h1 {

                font-size: 32px;

            }

            .navbar {

                padding: 20px;

            }

            .actions {

                flex-direction: column;

            }

        }
    </style>

</head>

<body>


    <!-- NAVBAR -->

    <div class="navbar">

        <div class="logo">

            📦 My Products

        </div>

        <div class="nav-links">

            <a href="sellerDashboard.php">

                <i class="bi bi-speedometer2"></i>
                Dashboard

            </a>

            <a href="addProduct.php">

                <i class="bi bi-plus-circle"></i>
                Add Product

            </a>

            <a href="logout.php">

                <i class="bi bi-box-arrow-right"></i>
                Logout

            </a>

        </div>

    </div>


    <!-- HERO -->

    <div class="hero">

        <div>

            <h1>

                Manage Products 🥬

            </h1>

            <p>

                View, edit, and manage your uploaded farm products

            </p>

        </div>

        <div class="hero-badge">

            <?php echo $totalProducts; ?>

            Products Listed

        </div>

    </div>


    <!-- PRODUCTS -->

    <?php if ($result->num_rows > 0) { ?>

        <div class="products">

            <?php

            while ($row = $result->fetch_assoc()) {

            ?>

                <div class="card">

                    <div class="image">

                        <?php if (!empty($row['image'])) { ?>

                            <img
                                src="uploads/<?php echo $row['image']; ?>">

                        <?php } else { ?>

                            <div class="placeholder">

                                🥬

                            </div>

                        <?php } ?>

                    </div>


                    <div class="content">

                        <div class="title">

                            <?php echo $row['product_name']; ?>

                        </div>

                        <div class="sku">

                            SKU:
                            <?php echo $row['sku_code']; ?>

                        </div>

                        <div class="price">

                            ₱<?php echo number_format($row['price'], 2); ?>

                        </div>


                        <?php

                        $stock = (int)$row['stock_level'];

                        if ($stock <= 0) {

                        ?>

                            <div class="stock out-stock">

                                <i class="bi bi-x-circle-fill"></i>
                                Out of Stock

                            </div>

                        <?php

                        } elseif ($stock <= 5) {

                        ?>

                            <div class="stock low-stock">

                                <i class="bi bi-exclamation-triangle-fill"></i>

                                <?php echo $stock; ?>

                                Low Stock

                            </div>

                        <?php

                        } else {

                        ?>

                            <div class="stock in-stock">

                                <i class="bi bi-check-circle-fill"></i>

                                <?php echo $stock; ?>

                                In Stock

                            </div>

                        <?php } ?>


                        <div class="actions">

                            <a
                                class="btn edit-btn"
                                href="editProduct.php?id=<?php echo $row['id']; ?>">

                                <i class="bi bi-pencil-square"></i>
                                Edit

                            </a>

                            <a
                                class="btn delete-btn"
                                href="deleteProduct.php?id=<?php echo $row['id']; ?>"
                                onclick="return confirm('Delete this product?')">

                                <i class="bi bi-trash3-fill"></i>
                                Delete

                            </a>

                        </div>

                    </div>

                </div>

            <?php } ?>

        </div>

    <?php } else { ?>

        <div class="empty">

            <h2>

                📭 No Products Yet

            </h2>

            <p>

                Start adding your farm products to sell on AgriMart

            </p>

            <a
                class="add-btn"
                href="addProduct.php">

                <i class="bi bi-plus-circle"></i>
                Add First Product

            </a>

        </div>

    <?php } ?>

</body>

</html>