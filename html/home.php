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
    <link rel="stylesheet" href="../css/home.css" type="text/css"/>
</head>
<body>
<div class="body">
    <?php
        if($_SESSION['username'] == null) {
            echo "<script>window.location.href = './login.php';</script>";
        }
    ?>
    <div class="header">
        <div id="goHome">
            <a href="home.php" style="text-decoration: none;">CookZilla</a>
        </div>
        <div class="search">
            <form id="search" method="post" action="" enctype="multipart/form-data">
                <div><input id="searchText" name="search" type="text" placeholder="Find a recipe"></div>
                <div id="tag">Tag<span class="caret"></span></div>
                <div style="margin-top: 3px;"><button class="btn btn-default">Search</button></div>
            </form>
        </div>
        <div id="username"><?php echo $_SESSION['username'];?></div>
    </div>
    
    <div id="tagContent" class="hidden">

    </div>
    

    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-9">
                <div class="jumbotron">
                    Welcome
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