<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 12/11/16
 * Time: 11:01 AM
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
                        <input id="searchText" name="search" type="text" class="form-control" placeholder="Find a recipe" style="color: grey;">
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
                    if (hasAGroup($_SESSION['username']) == false) {
                        echo "<script>alert('You must join a group to create event!')</script>";
                    }
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
                <a href="profile.php?id=self" style="text-decoration: none;" ><?php echo $_SESSION['username'];?></a>
                <a href="logout.php" style="margin-left: 15px;text-decoration: none;">Sign Out</a>
            </div>
        </div>
    </div>
    <div>
        <form id="create_event" action="../php/checkevent.php" method="post" style="width: 60%; margin-left: 20%" enctype="multipart/form-data">
            <div class="form-group">
                <p style="text-align: center; font-size: medium; font-weight: bold;">Event Title</p>
                <input type="text" class="form-control" name="etitle" maxlength="255" placeholder="Enter your event title">
                <small class="form-text text-muted">You must enter your event title.</small>
            </div>
            <div class="form-group">
                <p style="text-align: center; font-size: medium; font-weight: bold;">Choose your group</p>
                <?php
                    $groups = getUserGroup($_SESSION['username']);
                    echo "<select name='gid' style='margin-left: 43%; width: 14%;' class='form-control'>";
                    for($i = 0; $i < count($groups); $i++ ) {
                        echo "<option value='" . $groups[$i]['gid'] ."'>" . $groups[$i]['gname'] . "</option>";
                    }
                echo "</select><br>";
                ?>
            </div>

            <div class="form-group">
                <p style="text-align: center; font-size: medium; font-weight: bold;">Event Description</p>
                <textarea class="form-control" name="edesc" rows="10" maxlength="2000" placeholder="Enter your event description"></textarea>
            </div>

            <div class="form-group">
                <p style="text-align: center; font-size: medium; font-weight: bold;">Event Location</p>
                <input type="text" class="form-control" name="elocation" maxlength="255" placeholder="Enter your event location">
                <small class="form-text text-muted">You must enter your event location.</small>
            </div>

            <div class="form-group">
                <p style="text-align: center; font-size: medium; font-weight: bold;">Event Date Time</p>
                <input type="datetime-local" class="form-control" name="etime" style="margin-left: 36%; width: 28%">
            </div>

            <div align="center">
                <button type="submit" class="btn btn-primary" style="margin-bottom: 10px">Submit your Event!</button>
            </div>
        </form>
    </div>

    <div class="footer">
        <div>Copyright &copy; CookZilla TM. All Right Reserved.</div>
        <div>ADDRESS: 5 MetroTech, Brooklyn, 11201</div>
        <div>EMAIL: CookZilla@gmail.com</div>
    </div>
</body>

