<?php
session_start();
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../css/common.css" type="text/css"/>
    <link rel="stylesheet" href="../css/bootstrap.min.css" type="text/css"/>
    <link rel="stylesheet" href="../css/login.css" type="text/css"/>
</head>
<body>
<div class="body">
    <div class="header">
        <div id="goHome"><a href="../index.html">CookZilla</a></div>
        <div class="signButton" style="margin-right: 100px;"><a href="html/register.php">Sign Up</a></div>
        <div class="signButton"><a href="../html/login.php">Sign In</a></div>
    </div>
    
    <form id="login" class="register" method="post" style="margin-bottom: 300px" action="../php/user.php">
        <h3 class="formTitle">Sign In</h3>
        <hr>
        <div class="form-group">
            <input autofocus="autofocus" id="username" maxlength="32" minlength="1" name="username" placeholder="Username" type="text" required class="formInput">
            <input type="hidden" id="origin" name="origin" value="0">
        </div>
        <div class="form-group">
            <input id="password" name="password" placeholder="Password" type="password" required class="formInput">
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>

    <div class="footer">
        <div>Copyright &copy; CookZilla TM. All Right Reserved.</div>
        <div>ADDRESS: 5 MetroTech, Brooklyn, 11201</div>
        <div>EMAIL: CookZilla@gmail.com</div>
    </div>
</div>
</body>
</html>