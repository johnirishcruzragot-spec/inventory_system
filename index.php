<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inventory Management System</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, Helvetica, sans-serif;
}

body{
    background: #d6deff;
background: linear-gradient(188deg, rgba(214, 222, 255, 1) 0%, rgba(255, 255, 255, 1) 51%, rgba(217, 251, 255, 1) 100%);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    margin:0;
}

.container{
    width:900px;
    height:500px;
    background:#fff;
    border-radius:20px;
    overflow:hidden;
    display:flex;
    box-shadow:0 25px 60px rgba(0,0,0,0.25);
}


.left{
    width:50%;
    background:linear-gradient(rgba(0,91,187,.85),rgba(0,123,255,.85)),
    url("https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=900&q=80");
    background-size:cover;
    background-position:center;
    color:white;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    padding:40px;
    text-align:center;
}

.left h1{
    font-size:35px;
    margin-bottom:15px;
}

.left p{
    font-size:17px;
    line-height:28px;
}

.right{
    width:50%;
    display:flex;
    justify-content:center;
    align-items:center;
}

.login-box{
    width:80%;
}

.login-box h2{
    text-align:center;
    margin-bottom:30px;
    color:#0056b3;
}

.input-group{
    margin-bottom:20px;
}

.input-group label{
    display:block;
    margin-bottom:8px;
    color:#555;
    font-weight:bold;
}

.input-group input{
    width:100%;
    padding:13px;
    border:1px solid #ccc;
    border-radius:8px;
    font-size:15px;
    transition:.3s;
}

.input-group input:focus{
    border-color:#007bff;
    outline:none;
    box-shadow:0 0 8px rgba(0,123,255,.3);
}

button{
    width:100%;
    padding:14px;
    background:#007bff;
    color:white;
    border:none;
    border-radius:8px;
    font-size:17px;
    cursor:pointer;
    transition:.3s;
}

button:hover{
    background:#0056b3;
}

.register{
    text-align:center;
    margin-top:20px;
}

.register a{
    color:#007bff;
    text-decoration:none;
    font-weight:bold;
}

.register a:hover{
    text-decoration:underline;
}

@media(max-width:850px){

.container{
    width:95%;
    flex-direction:column;
    height:auto;
}

.left{
    width:100%;
    height:250px;
}

.right{
    width:100%;
    padding:40px 0;
}

}

</style>

</head>
<body>

<div class="container">

<div class="left">

<h1>DICT Inventory System</h1>

<p>
Inventory Management System<br>
Department of Information and Communications Technology
</p>

</div>

<div class="right">

<div class="login-box">

<h2>Login</h2>

<form action="login.php" method="POST">

<div class="input-group">
<label>Email</label>
<input 
    type="email" 
    name="email" 
    placeholder="Enter Email" 
    required>
</div>

<div class="input-group">
<label>Password</label>
<input 
    type="password" 
    name="password" 
    placeholder="Enter Password" 
    required>
</div>

<button type="submit" name="login">
LOGIN
</button>

</form>

</div>

</div>

</div>

</body>
</html>