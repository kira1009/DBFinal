<?php
require '../php/db_query.php';
session_start();
$username = $_SESSION['username'];
$groupArr = findNotJoinedGroup($username);
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
    <div class="container" style="min-height: 600px">
        <div class="lead" style="font-style: oblique">
            <?php
            if(empty($groupArr)) {
                echo "no group to join";
            } else {
                $size = count($groupArr);
                echo $size." groups to join";
            }
            ?>
        </div>
        <hr>
        <div class="row">
            <?php
            foreach ($groupArr as $g) {
                $pageContent = "<div class='col-lg-4'><h4 style='font-weight: bold'>";
                $pageContent = $pageContent.$g['gname']."</h4>";
                $pageContent = $pageContent."<p>Description: ".$g['description']."</p>";
                $pageContent = $pageContent."<p><a class='btn btn-default' href='../php/group_join.php?id=".$g['gid']."'role='button'>Join now</a></p></div>";
                echo $pageContent;
            }
            ?>
        </div>
        <br>
        <?php $joinedGroups = getUserGroup($username)?>
        <div class="lead" style="font-style: oblique">
            <?php
            if(empty($joinedGroups)) {
                echo "0 group joined ";
            } else {
                $size = count($joinedGroups);
                echo $size." groups joined";
            }
            ?>
        </div>
        <hr>
        <div class="row">
            <?php
            foreach ($joinedGroups as $g) {
                $pageContent = "<div class='col-lg-4'><h4 style='font-weight: bold'>";
                $pageContent = $pageContent.$g['gname']."</h4>";
                $pageContent = $pageContent."<p>Description: ".$g['description']."</p></div>";
                echo $pageContent;
            }
            ?>
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

