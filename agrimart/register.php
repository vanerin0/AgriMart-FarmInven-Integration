<!DOCTYPE html>
<html lang="en">

<head>

<title>AgriMart Register</title>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1"
/>

<link
href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
rel="stylesheet"
/>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
rel="stylesheet"
/>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{

min-height:100vh;

display:flex;

justify-content:center;
align-items:center;

background:
linear-gradient(
135deg,
#1b5e20,
#43a047,
#81c784
);

overflow:hidden;

position:relative;

}

/* BACKGROUND */

.circle{

position:absolute;

border-radius:50%;

background:
rgba(255,255,255,.12);

backdrop-filter:blur(10px);

}

.circle1{

width:350px;
height:350px;

top:-120px;
left:-80px;

}

.circle2{

width:260px;
height:260px;

bottom:-90px;
right:-70px;

}

.circle3{

width:150px;
height:150px;

top:120px;
right:150px;

}

/* CARD */

.register-card{

width:460px;

background:
rgba(255,255,255,.96);

backdrop-filter:blur(20px);

padding:45px;

border-radius:30px;

box-shadow:
0 15px 40px rgba(0,0,0,.18);

position:relative;

z-index:10;

animation:fadeIn .7s ease;

}

@keyframes fadeIn{

from{

opacity:0;
transform:translateY(20px);

}

to{

opacity:1;
transform:translateY(0);

}

}

/* HEADER */

.logo{

width:95px;
height:95px;

border-radius:28px;

background:
linear-gradient(
135deg,
#2e7d32,
#66bb6a
);

display:flex;

justify-content:center;
align-items:center;

margin:auto;

font-size:42px;

color:white;

box-shadow:
0 10px 25px rgba(46,125,50,.35);

margin-bottom:25px;

}

h1{

text-align:center;

font-size:34px;

font-weight:700;

color:#1b4332;

margin-bottom:10px;

}

.subtitle{

text-align:center;

color:#6c757d;

font-size:15px;

margin-bottom:35px;

}

/* INPUTS */

.form-group{

margin-bottom:22px;

position:relative;

}

.form-group i{

position:absolute;

top:18px;
left:18px;

color:#43a047;

font-size:18px;

}

input,
select{

width:100%;

padding:16px 18px 16px 50px;

border:none;

border-radius:16px;

background:#f1f5f9;

font-size:15px;

transition:.3s;

outline:none;

appearance:none;

}

input:focus,
select:focus{

background:white;

box-shadow:
0 0 0 4px rgba(67,160,71,.2);

}

/* SELECT ICON */

.select-icon{

position:absolute;

right:18px;
top:18px;

color:#43a047;

pointer-events:none;

}

/* BUTTON */

button{

width:100%;

padding:16px;

border:none;

border-radius:16px;

background:
linear-gradient(
135deg,
#2e7d32,
#43a047
);

color:white;

font-size:16px;

font-weight:600;

cursor:pointer;

transition:.3s;

box-shadow:
0 10px 20px rgba(46,125,50,.25);

}

button:hover{

transform:
translateY(-3px);

box-shadow:
0 15px 25px rgba(46,125,50,.35);

}

/* LINKS */

.links{

margin-top:25px;

text-align:center;

font-size:14px;

color:#555;

}

.links a{

text-decoration:none;

color:#2e7d32;

font-weight:600;

transition:.3s;

}

.links a:hover{

color:#1b5e20;

}

/* FOOTER */

.footer{

margin-top:25px;

text-align:center;

font-size:12px;

color:#999;

}

/* MOBILE */

@media(max-width:500px){

.register-card{

width:92%;

padding:35px 25px;

}

h1{

font-size:28px;

}

}

</style>

</head>

<body>

<!-- BACKGROUND -->

<div class="circle circle1"></div>

<div class="circle circle2"></div>

<div class="circle circle3"></div>

<!-- REGISTER CARD -->

<div class="register-card">

<div class="logo">

🌱

</div>

<h1>

Create Account

</h1>

<div class="subtitle">

Join AgriMart and start buying or selling fresh farm products

</div>

<form
action="processRegister.php"
method="POST"
>

<div class="form-group">

<i class="bi bi-person-fill"></i>

<input
type="text"
name="fullname"
placeholder="Enter your full name"
required
>

</div>

<div class="form-group">

<i class="bi bi-envelope-fill"></i>

<input
type="email"
name="email"
placeholder="Enter your email"
required
>

</div>

<div class="form-group">

<i class="bi bi-lock-fill"></i>

<input
type="password"
name="password"
placeholder="Create password"
required
>

</div>

<div class="form-group">

<i class="bi bi-person-badge-fill"></i>

<select name="role">

<option value="customer">

Customer

</option>

<option value="seller">

Seller

</option>

</select>

<i class="bi bi-chevron-down select-icon"></i>

</div>

<button type="submit">

<i class="bi bi-person-plus-fill"></i>

Create Account

</button>

</form>

<div class="links">

Already have an account?

<a href="login.php">

Login

</a>

</div>

<div class="footer">

© 2026 AgriMart —
Fresh Farm Marketplace

</div>

</div>

</body>

</html>