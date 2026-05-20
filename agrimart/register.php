<!DOCTYPE html>
<html>
<head>

<title>AgriMart Register</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Poppins,sans-serif;
}

body{

height:100vh;

display:flex;
justify-content:center;
align-items:center;

background:
linear-gradient(
135deg,
#43a047,
#81c784
);

}

.card{

background:white;

width:420px;

padding:40px;

border-radius:25px;

box-shadow:
0 15px 35px rgba(0,0,0,.15);

}

h1{

text-align:center;
margin-bottom:25px;
color:#2e7d32;

}

input{

width:100%;
padding:15px;

margin-bottom:15px;

border:1px solid #ddd;

border-radius:12px;

}

button{

width:100%;
padding:15px;

border:none;

border-radius:12px;

background:#2e7d32;

color:white;

font-size:16px;

cursor:pointer;

}

button:hover{

opacity:.9;

}

.bottom{

text-align:center;
margin-top:20px;

}

a{

text-decoration:none;
color:#2e7d32;

}

</style>

</head>

<body>

<div class="card">

<h1>🌱 AgriMart Register</h1>

<form
action="processRegister.php"
method="POST"
>

<input
type="text"
name="fullname"
placeholder="Full Name"
required
>

<input
type="email"
name="email"
placeholder="Email"
required
>

<input
type="password"
name="password"
placeholder="Password"
required
>

<select
name="role"
style="
width:100%;
padding:15px;
margin-bottom:15px;
border-radius:12px;
"
>

<option value="customer">

Customer

</option>

<option value="seller">

Seller

</option>

</select>

<button>

Create Account

</button>

</form>

<div class="bottom">

Already have account?

<a href="login.php">

Login

</a>

</div>

</div>

</body>
</html>