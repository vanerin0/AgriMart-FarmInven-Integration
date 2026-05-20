<?php

include("checkAuth.php");

if($_SESSION['role']!="admin"){

die("Unauthorized Access");

}

$conn=new mysqli(
"localhost",
"root",
"",
"agrimart_db"
);


if(isset($_GET['approve'])){

$id=$_GET['approve'];

$conn->query(

"UPDATE seller_profiles
SET status='Approved'
WHERE id='$id'"

);

header(
"location:approveSeller.php"
);

exit();

}


if(isset($_GET['reject'])){

$id=$_GET['reject'];

$conn->query(

"UPDATE seller_profiles
SET status='Rejected'
WHERE id='$id'"

);

header(
"location:approveSeller.php"
);

exit();

}


$result=$conn->query(

"SELECT

sp.*,
u.fullname,
u.email

FROM seller_profiles sp

JOIN users u
ON sp.user_id=u.id

ORDER BY sp.id DESC"

);

?>

<!DOCTYPE html>

<html>

<head>

<title>

Approve Sellers

</title>

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

border-radius:20px;

display:flex;

justify-content:space-between;
align-items:center;

box-shadow:
0 10px 25px rgba(0,0,0,.08);

margin-bottom:30px;

}

.logo{

font-size:28px;
font-weight:700;
color:#2e7d32;

}

.nav a{

margin-left:20px;

text-decoration:none;

color:#555;

font-weight:500;

}

.title{

margin-bottom:25px;

}

.title h1{

font-size:35px;

color:#222;

}

.card{

background:white;

padding:25px;

border-radius:20px;

margin-bottom:20px;

box-shadow:
0 10px 25px rgba(0,0,0,.08);

}

.shop{

font-size:22px;

font-weight:700;

margin-bottom:15px;

color:#2e7d32;

}

.info{

margin-bottom:10px;

color:#555;

}

.badge{

display:inline-block;

padding:8px 15px;

border-radius:30px;

font-size:13px;

font-weight:600;

margin-top:15px;

}

.pending{

background:#fff3cd;
color:#856404;

}

.approved{

background:#d4edda;
color:#155724;

}

.rejected{

background:#f8d7da;
color:#721c24;

}

.btn{

display:inline-block;

padding:12px 18px;

border-radius:10px;

color:white;

text-decoration:none;

margin-top:15px;

margin-right:10px;

}

.approve{

background:#2e7d32;

}

.reject{

background:#d32f2f;

}

.empty{

background:white;

padding:50px;

text-align:center;

border-radius:20px;

}

</style>

</head>

<body>


<div class="navbar">

<div class="logo">

🌱 AgriMart Admin

</div>

<div class="nav">

<a href="adminDashboard.php">

Dashboard

</a>

<a href="logout.php">

Logout

</a>

</div>

</div>


<div class="title">

<h1>

Seller Approval

</h1>

</div>


<?php

if(
$result->num_rows==0
){

?>

<div class="empty">

No seller applications found

</div>

<?php

}


while(
$row=
$result->fetch_assoc()
){

$status=
strtolower(
$row['status']
);

?>

<div class="card">

<div class="shop">

🏪

<?php
echo $row['shop_name'];
?>

</div>


<div class="info">

Owner:

<b>

<?php
echo $row['fullname'];
?>

</b>

</div>


<div class="info">

Email:

<?php
echo $row['email'];
?>

</div>


<div class="info">

Contact:

<?php
echo $row['contact'];
?>

</div>


<div class="info">

Address:

<?php
echo $row['address'];
?>

</div>


<div
class="badge <?php echo $status;?>"
>

<?php
echo $row['status'];
?>

</div>

<br>


<?php

if(
$row['status']=="Pending"
){

?>

<a
class="btn approve"
href="
approveSeller.php?approve=
<?php echo $row['id'];?>
">

Approve

</a>


<a
class="btn reject"
href="
approveSeller.php?reject=
<?php echo $row['id'];?>
">

Reject

</a>

<?php } ?>

</div>

<?php } ?>


</body>

</html>