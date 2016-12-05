<?php
session_start();
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../css/common.css" type="text/css"/>
    <link rel="stylesheet" href="../css/bootstrap.min.css" type="text/css"/>
    <link rel="stylesheet" href="../css/login_register.css" type="text/css"/>
</head>
<body>
<div class="body">
    <div class="header">
        <div id="goHome">
            <a href="../index.html" style="text-decoration : none;">CookZilla</a>
        </div>
        <div class="signButton" style="margin-right: 100px;">
            <a href="./register.php" style="text-decoration : none;">Sign Up</a>
        </div>
        <div class="signButton">
            <a href="./login.php" style="text-decoration : none;"z>Sign In</a>
        </div>
    </div>

    <form id="register" class="register" method="post" action="../php/user.php" enctype="multipart/form-data">
        <h3 class="formTitle">Sign Up</h3>
        <hr>
        <div class="form-group">
            <input autofocus="autofocus" id="username" maxlength="32" minlength="1" name="username" placeholder="Username" type="text" required class="formInput">
            <input type="hidden" id="origin" name="origin" value="1">
        </div>
        <div class="form-group">
            <input autofocus="autofocus" id="realname" maxlength="255" minlength="1" name="realname" placeholder="Real name" type="text" required class="formInput">
        </div>
        <div class="form-group">
            <input id="password" name="password" placeholder="Password" type="password" required class="formInput">
        </div>
        <div class="form-group">
            <textarea id="profile" name="profile" placeholder="Enter your self description here" class="formInput formText" maxlength="255"></textarea>
        </div>
        <div class="form-group">
            <input id="uicon" name="uicon" type="file" required class="formInput">
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