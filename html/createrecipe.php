<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 12/8/16
 * Time: 2:01 PM
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
        <form id="create_recipe" action="../php/checkrecipe.php" method="post" style="width: 60%; margin-left: 20%" enctype="multipart/form-data">
            <div class="form-group">
                <p style="text-align: center; font-size: medium; font-weight: bold;">Title</p>
                <input type="text" class="form-control" name="title" id="recipe_title" maxlength="255" placeholder="Enter your recipe title">
                <small class="form-text text-muted">You must enter your recipe title.</small>
            </div>
            <div class="form-group">
                <p style="text-align: center; font-size: medium; font-weight: bold;">Serving</p>
                <input type="number" min="0" step="1" class="form-control" name="serving" placeholder="Enter your serving">
            </div>
            <br>
            <div class="form-group" style="margin-bottom: 30px;min-height: 60px">
                <p style="text-align: center; font-size: medium; font-weight: bold;">Tags</p>
                <?php
                    $tags = getTags();
                    foreach ($tags as $tag) {
                        echo "<div style='width: 33%; float: left'><input type='checkbox' name='tags[]' value='" . $tag['tname'] . "'>" . $tag['tname'] . "</div>";
                    }
                ?>
            </div>
            <div>
                <p style="text-align: center; font-size: medium; font-weight: bold;">Ingredient</p>
            </div>
            <div class="form-inline" id="ingredient" style="margin-left: 10%;"></div>
            <div align="center">
                <br>
                <input type="button" value="Add More Ingredients!" class="btn" onclick="addIngredient()">
            </div>
            <br>
            <div class="form-group">
                <p style="text-align: center; font-size: medium; font-weight: bold;">Steps:</p>
                <textarea class="form-control" name="step" rows="10"></textarea>
            </div>
            <br>
            <p style="text-align: center; font-size: medium; font-weight: bold;">Images:</p>
            <div id="recipeImg" style="margin-left: 20%;"></div>
            <div align="center">
                <br>
                <input type="button" value="Add More Images!" class="btn" onclick="addImg()">
            </div>
            <br>
            <div id="recipeLink">
                <input type="text" class="form-control" name="link1" placeholder="Enter your related recipe link">
                <br>
                <input type="text" class="form-control" name="link2" placeholder="Enter your related recipe link">
                <br>
                <input type="text" class="form-control" name="link3" placeholder="Enter your related recipe link">
            </div>
            <br>
            <div align="center">
                <button type="submit" class="btn btn-primary" style="margin-bottom: 10px">Submit your recipe!</button>
            </div>
        </form>
        <script>
            function addIngredient() {
                var obj = document.getElementById("ingredient");
                var newDiv = document.createElement("div");
                newDiv.innerHTML = "<div style='width: 33%; float: left'><label>Amount: </label>" +
                    "<input type='number' min='0' name='amount[]' class='form-control' ></div>" +
                    "<div style='width: 33%; float: left'><label style='margin-left: 40px'>Unit: </label>" +
                    "<select name='unit[]' class='form-control'>" +
                    "<option value='gram'>gram</option>" +
                    "<option value='ml'>ml</option>" +
                    "<option value='l'>l</option>" +
                    "<option value='item'>item</option>" +
                    "</select></div>" +
                    "<label>Name: </label>" +
                    "<input type='text' class='form-control' name='iname[]'><br>";
                obj.appendChild(newDiv);
            }

            function addImg() {
                var obj = document.getElementById("recipeImg");
                var newDiv = document.createElement("div");
                newDiv.innerHTML = "<input type='file' name='image[]'><br>";
                obj.appendChild(newDiv);
            }
        </script>
    </div>

    <div class="footer">
        <div>Copyright &copy; CookZilla TM. All Right Reserved.</div>
        <div>ADDRESS: 5 MetroTech, Brooklyn, 11201</div>
        <div>EMAIL: CookZilla@gmail.com</div>
    </div>
</body>
