<?php
require '../php/db_query.php';
session_start();
$rid = $_GET['id'];
$basicInfo = getRecipeInfoById($rid);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CookZilla</title>
    <link rel="stylesheet" href="../css/common.css" type="text/css"/>
    <link rel="stylesheet" href="../css/bootstrap.min.css" type="text/css"/>
    <link rel="stylesheet" href="../css/home.css" type="text/css"/>
    <link rel="stylesheet" href="../css/recipe.css" type="text/css"/>
    <script src="../js/jquery-3.1.1.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#tags").hide();
        })
    </script>
</head>
<body>
<div class="body">
    <?php
    $username = $_SESSION['username'];
    if($username == null) {
        echo "<script>window.location.href = './login.php';</script>";
    }
    ?>
    <div class="header">
        <div id="goHome">
            <a href="home.php" style="text-decoration: none;">CookZilla</a>
        </div>
        <form method="post" action="search.php" enctype="multipart/form-data">
            <div class="search">
                <div>
                    <input id="searchText" name="search" type="text" placeholder="Find a recipe" style="color: grey;" value="<?php echo $keyword?>">
                </div>
                <div id="tag" style="cursor:pointer;">
                    Tag<span class="caret"></span>
                </div>
                <div style="margin-top: 3px;">
                    <button class="btn btn-default" style="color: yellowgreen">Search</button>
                </div>
            </div>
            <div id="username">
                <?php echo $username;?>
                <a href="logout.php"  style="margin-left: 10px;text-decoration: none;">Sign Out</a>
            </div>
            <script type="text/javascript">
                $("#tag").click(function(){
                    $("#tags").toggle();
                });
            </script>
            <div id="tags" class="tags">
                <?php
                $count = 1;
                foreach (getTags() as $element) {
                    echo "<div id='tagOption".$count."' style='width: 30%'><input type='checkbox' name=selectedTag[] value=".$element['tname']." style='width: 20px; float:left;'/><label>".$element['tname']."</label></div>";
                    $count = $count + 1;
                }
                ?>
                <div id="addTag" class="btn btn-primary" style="float: right; margin-right: 3px;">OK</div>
                <script>
                    $("#addTag").click(function () {
                        $("#tags").hide();
                    });
                </script>
            </div>
        </form>
    </div>
    <div class="container">
        <div class="blog-header">
            <h1 class="blog-title" style="font-weight: bold"><?php echo $basicInfo[0]['rtitle'] ?></h1>
            <p class="lead blog-description"></p>
        </div>
        <h2 style="font-weight: bold"></h2>

    </div>
    <div class="footer">
        <div>Copyright &copy; CookZilla TM. All Right Reserved.</div>
        <div>ADDRESS: 5 MetroTech, Brooklyn, 11201</div>
        <div>EMAIL: CookZilla@gmail.com</div>
    </div>
</div>
</body>
</html>

