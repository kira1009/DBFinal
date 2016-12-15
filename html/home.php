<?php
require '../php/db_query.php';
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
    <script src="https://cdn.static.runoob.com/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.static.runoob.com/libs/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<!--    <script src="../js/jquery-3.1.1.min.js"></script>-->
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
                        <input id="searchText" name="search" type="text" class="form-control" placeholder="Find a recipe" style="color: grey;">
                    </div>
                    <div id="tag" style="cursor:pointer;">
                        Tag<span class="caret"></span>
                    </div>
                    <div style="margin-top: 3px;">
                        <button class="btn btn-default" style="color: yellowgreen">Search</button>
                    </div>
                </div>
                <div id="username">
                    <a href="profile.php?id=self" style="text-decoration: none;" ><?php echo $username;?></a>
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
            <div class="row">
                <div class="col-xs-12 col-sm-9">
                    <div class="jumbotron">
                        <h3>Welcome to our website <?php echo $username?>!</h3>
                        <br>
                        <a href="createrecipe.php" class="btn btn-primary creatButton">Create your recipe</a>
                        <a href="creategroup.php" class="btn btn-primary creatButton">Create a group</a>
                        <?php
                            if(hasAGroup($username)){
                                echo "<a href='createevent.php' class='btn btn-primary creatButton'>Create a event</a>";
                            }
                        ?>
                    </div>
                    <div class="row">
                        <h3>Your recipes</h3>
                        <hr>
                        <?php
//                            $count = 0;
                            $recipes = getUserRecipe($username);
                            if(empty($recipes)){
                                echo "<p>no recipe now, <a href='createrecipe.php'>create one</a></p>";
                            }
                            foreach ($recipes as $recipe) {
//                                if($count > 2) break;
//                                $count++;
                                $pageContent = "<div class='col-xs-6 col-lg-4'><h4>";
                                $pageContent = $pageContent.$recipe['rtitle']."</h4>";
                                $imgDir = explode(';', $recipe['rimage'])[0];
                                $pageContent = $pageContent."<p><img src='".$imgDir."' class='recipeImg' onerror=\"this.src='../img/default.jpg'\"/>";
                                $pageContent = $pageContent."<p><a class='btn btn-default' href='recipe.php?id=".$recipe['rid']."'role='button'>View details »</a></p></div>";
                                echo $pageContent;
                            }
                        ?>
                    </div>
                    <div class="row">
                        <h3>Recent viewed</h3>
                        <hr>
                        <?php
                            $count = 0;
                            $recipes = getUserViewedRecipe($username);
                            if(empty($recipes)){
                                echo "<p>You have not viewed any recipe, try to search one</p>";
                            }
                            foreach ($recipes as $recipe) {
                                if($count > 2) break;
                                $count++;
                                $pageContent = "<div class='col-xs-6 col-lg-4'><h4>";
                                $pageContent = $pageContent.$recipe['rtitle']."</h4>";
                                $imgDir = explode(';', $recipe['rimage'])[0];
                                $pageContent = $pageContent."<p><img class='recipeImg' src='".$imgDir."' onerror=\"this.src='../img/default.jpg'\"/>";
                                $pageContent = $pageContent."<p><a class='btn btn-default' href='recipe.php?id=".$recipe['rid']."'role='button'>View details »</a></p></div>";
                                echo $pageContent;
                            }
                        ?>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar">
                    <div class="list-group">
                        <div class="list-group-item">
                            <div class="rightColTitle"><h4>Groups</h4></div>
                            <?php
                                $groups = getUserGroup($username);
                                foreach($groups as $group) {
                                    echo "<div style='width: 100%;font-weight: bold;'>".$group['gname']."<a class='popshow' href='#' title='Description' data-container='body' data-toggle='popover' data-placement='right' data-content='".$group['description']."'>&nbsp;...</a></div>";
                                    echo "<hr style='margin: auto'>";
                                    $members = getGroupUsersById($group['gid']);
                                    foreach ($members as $member) {
                                        $memberName = $member['uname'];
                                        echo "<div style='width: 100%;'><a href='profile.php?id=".$memberName."'>".$memberName."</a></div>";
                                    }
                                    echo "<div style='width: 100%;text-align: center'><a href='../php/group_leave.php?id=".$group['gid']."'>Leave this group</a></div>";
                                    echo "<br>";
                                }
                            ?>
                            <script>
                                $(function (){
                                    $("[data-toggle='popover']").popover();
                                });
                            </script>
                            <div style="width: inherit; text-align: center;"><a href="joinGroup.php">+join group</a></div>
                        </div>
                        <div class="list-group-item">
                            <div class="rightColTitle"><h4>Events</h4></div>
                            <?php
                                $rsvpEvents = getRsvpEvent($username);
                                foreach ($rsvpEvents as $event) {
                                    $etitle = $event['etitle'];
                                    if(strlen($etitle) > 17) {
                                        $etitle = substr($etitle, 0, 17)."...";
                                    }
                                    echo "<div style='width: 100%;'><a href='event.php?eid=".$event['eid']."'>".$etitle."</a><div class='eventStat'><a href='../php/cancel_event.php?id=".$event['eid']."'>cancel rsvp</a></div></div>";
                                }
                                $nonRsvpEvents = getUserGroupButNoRsvpEvent($username);
                                foreach ($nonRsvpEvents as $event) {
                                    $etitle = $event['etitle'];
                                    if(strlen($etitle) > 17) {
                                        $etitle = substr($etitle, 0, 17)."...";
                                    }
                                    echo "<div style='width: 100%; color: grey;'><a href='event.php?eid=".$etitle."'>".$etitle."<a href='../php/reserve_event.php?id=".$event['eid']."' class='eventStat'>rsvp now</a></div>";
                                }
                            ?>
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
