<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 12/10/16
 * Time: 10:55 PM
 */
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
        if($_SESSION['username'] == null) {
            echo "<script>window.location.href = './login.php';</script>";
        }
        ?>
        <div class="header">
            <div id="goHome">
                <a href="home.php" style="text-decoration: none;">CookZilla</a>
            </div>
            <form method="post" action="../html/search.php" enctype="multipart/form-data">
                <div class="search">
                    <div>
                        <input id="searchText" name="search" type="text" placeholder="Find a recipe" style="color: grey;">
                    </div>
                    <div id="tag" style="cursor:pointer;">
                        Tag<span class="caret"></span>
                    </div>
                    <div style="margin-top: 3px;">
                        <button class="btn btn-default" style="color: yellowgreen">Search</button>
                    </div>
                </div>
                <script type="text/javascript">
                    $("#tag").click(function(){
                        $("#tags").toggle();
                    });
                </script>
                <div id="tags" class="tags">
                    <?php
                    require '../php/db_query.php';
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
            <div id="username">
                <?php echo $_SESSION['username'];?>
                <a href="logout.php" style="margin-left: 15px;text-decoration: none;">Sign Out</a>
            </div>
        </div>
    </div>

    <div>
        <form id="create_group" action="../php/checkgroup.php" method="post" style="width: 60%; margin-left: 20%" enctype="multipart/form-data">
            <div class="form-group">
                <p style="text-align: center; font-size: medium; font-weight: bold;">Group Name</p>
                <input type="text" class="form-control" name="gname" maxlength="50" placeholder="Enter your group name">
                <small class="form-text text-muted">You must enter your group name.</small>
            </div>

            <div class="form-group">
                <p style="text-align: center; font-size: medium; font-weight: bold;">Description:</p>
                <textarea class="form-control" name="description" rows="10"></textarea>
            </div>

            <div align="center">
                <button type="submit" class="btn btn-primary" style="margin-bottom: 10px">Submit to create your group!</button>
            </div>
        </form>
    </div>

    <div class="footer">
        <div>Copyright &copy; CookZilla TM. All Right Reserved.</div>
        <div>ADDRESS: 5 MetroTech, Brooklyn, 11201</div>
        <div>EMAIL: CookZilla@gmail.com</div>
    </div>
</body>
</html>
