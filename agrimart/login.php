<!DOCTYPE html>
<html>

<head>

<title>Login</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">

<style>

body{

font-family:Poppins;

display:flex;

justify-content:center;
align-items:center;

height:100vh;

background:
linear-gradient(
135deg,
#2e7d32,
#66bb6a
);

}

.card{

background:white;

width:400px;

padding:40px;

border-radius:25px;

}

input{

width:100%;

padding:15px;

margin-bottom:15px;

border-radius:12px;

border:1px solid #ddd;

}

button{

width:100%;

padding:15px;

background:#2e7d32;

border:none;

border-radius:12px;

color:white;

}

h1{

text-align:center;
margin-bottom:25px;

}

</style>

</head>

<body>

<div class="card">

<h1>

🌱 Login

</h1>

<form
action="processLogin.php"
method="POST"
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

<button>

Login

</button>

</form>

</div>

</body>

</html>