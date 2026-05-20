<?php

include("checkAuth.php");

if($_SESSION['role']!="admin"){

die("Unauthorized");

}

$conn=new mysqli(
"localhost",
"root",
"",
"agrimart_db"
);

if(isset($_GET['id'])){

$id=$_GET['id'];

$conn->query(

"UPDATE seller_profiles

SET status='Approved'

WHERE id='$id'"

);

}

$result=$conn->query(

"SELECT
sp.*,
u.fullname

FROM seller_profiles sp

JOIN users u

ON sp.user_id=u.id

WHERE status='Pending'"

);

while(
$row=
$result->fetch_assoc()
){

echo"

<h2>

".$row['shop_name']."

</h2>

Owner:
".$row['fullname']."

<br>

".$row['contact']."

<br><br>

<a href='approveSeller.php?id=".$row['id']."'>Approve</a>

<hr>

";

}