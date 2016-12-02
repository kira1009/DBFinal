<?php
session_start();
$_POST['origin'] = '1'; //0-login; 1-register.
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="../css/common.css" type="text/css"/>
    <link rel="stylesheet" href="../css/bootstrap.min.css" type="text/css"/>
</head>
<body>
<div class="body">
    <ul id="menu" class="menu">
        <li><a href="../index.html">CookZilla</a></li>
        <li class="gap">&nbsp;</li>
        <li><a href="login.html" class="button">Sign In</a></li>
        <li><a href="register.php" class="button">Sign Up</a></li>
    </ul>

    <form id="register" class="register" method="post" action="home.php">
        <h3 class="formTitle">Sign Up</h3>
        <hr>
        <div class="form-group">
            <input autofocus="autofocus" id="username" maxlength="32" minlength="1" name="username" placeholder="Username" type="text" required class="formInput">
        </div>
        <div class="form-group">
            <input autofocus="autofocus" id="realname" maxlength="255" minlength="1" name="realname" placeholder="Real name" type="text" required class="formInput">
        </div>
        <div class="form-group">
            <input id="password" name="password" placeholder="Password" type="password" required class="formInput">
        </div>
        <div class="form-group">
            <textarea id="profile" name="profile" placeholder="Enter your self description here" class="formInput formText"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Sign up for CookZilla</button>
    </form>


    <div class="footer">
        <div>Copyright &copy; CookZilla TM. All Right Reserved.</div>
        <div>ADDRESS: 5 MetroTech, Brooklyn, 11201</div>
        <div>EMAIL: CookZilla@gmail.com</div>
    </div>
</div>
</body>
</html>