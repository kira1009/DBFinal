<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CookZilla</title>
    <link rel="stylesheet" href="../css/common.css" type="text/css"/>
    <link rel="stylesheet" href="../css/bootstrap.min.css" type="text/css"/>
</head>
<body>
<div class="body">
    <?php
        if($_SESSION['username'] == null) {
            echo "<script>window.location.href = './login.php';</script>";
        }
    ?>
    <div id="tagContent" class="hidden">

    </div>
    <ul id="menu" class="menu">
        <li><a href="./home.php">CookZilla</a></li>
        <li class="search">
            <input id="search" name="search" class="searchText" type="text" placeholder="Find a recipe">
        </li>
        <li class="tag">
            <a href="#" id="tag" onclick="">Tag<span class="caret"></span></a>
        </li>
        <li style="margin-right: 300px;"><button class="btn btn-default">Search</button></li>
        <li>
            <a href="#" data-toggle="dropdown" aria-expanded="false" style="font-size: 25px">
                <?php
                echo $_SESSION['username'];
                ?>
            </a>
        </li>
    </ul>

    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-9">
                <div class="jumbotron">
                    Welcome to our website <?php echo $_SESSION['username']?>
                    <br>
                    <a href="createrecipe.php" class="btn btn-primary">Create your recipe</a>
                </div>
                <div class="row">
                    <h3>Your recipes</h3>
                    <hr>
                    <?php

                    ?>
                </div>
                <div class="row">
                    <h3>Recent viewed</h3>
                    <hr>
                    <!--                        recipes -->
                </div>
            </div>
            <div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar">
                <div class="list-group">
                    <div class="list-group-item">
                        <h4>Group</h4>
                    </div>
                    <div class="list-group-item">
                        <h4>Events</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer">
        <div>Copyright &copy; CookZilla TM. All Right Reserved.</div>
        <div>ADDRESS: 5 MetroTech, Brooklyn, 11201</div>
        <div>EMAIL: CookZilla@gmail.com</div>
    </div>
</div>
</body>
</html>