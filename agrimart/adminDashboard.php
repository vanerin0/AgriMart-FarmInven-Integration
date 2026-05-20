<?php

include("checkAuth.php");

if($_SESSION['role']!="admin"){

die("Unauthorized");

}

?>

<!DOCTYPE html>
<html>
<head>

<title>Admin Dashboard</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">

<style>

body{

font-family:Poppins;
padding:40px;

background:
linear-gradient(
135deg,
#eef7ee,
#f5f7fa
);

}

.card{

background:white;

padding:40px;

border-radius:25px;

max-width:900px;

margin:auto;

box-shadow:
0 10px 30px rgba(0,0,0,.08);

}

.btn{

display:inline-block;

padding:15px 25px;

margin-right:15px;
margin-top:15px;

background:#2e7d32;

color:white;

text-decoration:none;

border-radius:12px;

}

</style>

</head>

<body>

<div class="card">

<h1>

🛠 Admin Dashboard

</h1>

<p>

Welcome

<b>

<?php echo $_SESSION['fullname']; ?>

</b>

</p>

<br>

<a
class="btn"
href="approveSeller.php"
>

Approve Sellers

</a>

<a
class="btn"
href="approvedOrders.php"
>

Approve Orders

</a>

<a
class="btn"
href="logout.php"
>

Logout

</a>

</div>

</body>
</html>