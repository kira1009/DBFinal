<?php
require '../php/db_query.php';
date_default_timezone_set('America/New_York');
session_start();
$rid = $_GET['id'];
$basicInfo = getRecipeInfoById($rid);
$username = $_SESSION['username'];
$url = $_SERVER['HTTP_REFERER'];
if(strpos($url, "search.php") || strpos($url, "home.php")) {
    record_usr_behavior(0, $rid, date('Y-m-d H:i:s'),$username);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CookZilla</title>
    <link rel="stylesheet" href="../css/common.css" type="text/css"/>
    <link rel="stylesheet" href="../css/bootstrap.min.css" type="text/css"/>
    <link rel="stylesheet" href="../css/home.css" type="text/css"/>
    <link rel="stylesheet" href="../css/recipe_refractor.css" type="text/css"/>
    <link rel="stylesheet" href="../css/nivo-slider.css" type="text/css"/>
    <link rel="stylesheet" href="../css/default.css" type="text/css"/>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js" type="text/javascript"></script>
    <script src="../js/jquery.nivo.slider.js"></script>
    <script>
        $(document).ready(function () {
            $("#tags").hide();
        })
    </script>
    <script type="text/javascript">
        $(window).load(function () {
            $('#slider').nivoSlider();
        });
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
        <div>
            <h1 style="font-weight: bold"><?php echo $basicInfo[0]['rtitle'] ?></h1>
            <hr style="margin-bottom: auto">
            <p style="color: grey"><?php echo $basicInfo[0]['serving']." servings";?></p>
            <?php
                $tags = getRecipeTagById($rid);
                if (empty($tags)) {
                    echo "<p style='color: grey; margin-top: -10px'>no tag</p>";
                }else {
                    $showTag = "";
                    foreach ($tags as $tag) {
                        $showTag = $showTag."#".$tag['tname']."\t";
                    }
                    echo "<p style='color: grey;margin-top: -10px;'>".$showTag."</p>";
                }
            ?>
        </div>

        <div class="theme-default" style="min-height: 400px; width: 100%;">
            <div style="float: left;width: 60%;">
                <h2>Ingredients</h2>
                <hr style="width: 90%; margin-top: auto;float: left">
                <br>
                <?php
                    $ingredients = getRecipeIngredientById($rid);
                    if(empty($ingredients)){
                        echo "<p>no ingredient posted</p>";
                    }else{
                        foreach ($ingredients as $ingredient) {
                            $amount = $ingredient['amount'];
                            $unit = $ingredient['unit'];
                            $name = $ingredient['iname'];
                            echo "<p>".$amount." ".$unit." of ".$name."</p>";
                        }
                    }

                ?>
            </div>
            <div id="slider" class="nivoSlider">
                <?php
                    $images = $basicInfo[0]['rimage'];
                    if(empty($images)){
                        echo "<img src='../img/default.jpg' />";
                    }else{
                        $imgDirs = explode(';', $images);
                        foreach ($imgDirs as $imgDir) {
                            if(empty($imgDir)){
                                continue;
                            }
                            echo "<img src='".$imgDir."' alt='no image' onerror=\"this.src='../img/default.jpg'\"/>";
                        }
                    }

                ?>
            </div>
        </div>
        <div class="description">
            <h2>Description</h2>
            <hr>
            <p>
                <?php
                echo str_replace("\\r\\n", "</br>", $basicInfo[0]['rtext']);
                ?>
            </p>
        </div>
        <div class="review container" style="padding-left: 0px">
            <h2>Related Recipes</h2>
            <hr>
            <?php
                $relatedRecipes = getRelatedRid($rid);
                if (count($relatedRecipes) == 0) {
                    echo "<p>No such related recipes!</p>";
                } else {
                    foreach ($relatedRecipes as $relatedRecipe) {
                        $pageContent = "<div class='col-lg-4'><h4>";
                        $pageContent = $pageContent.$relatedRecipe['rtitle']."</h4>";
                        $img = explode(';', $relatedRecipe['rimage'])[0];
                        $pageContent = $pageContent."<p><img src='".$img."' class='recipeImg' onerror=\"this.src='../img/default.jpg'\"/>";
                        $pageContent = $pageContent."<p>Created by: ".$relatedRecipe['uname']."</p>";
                        $pageContent = $pageContent."<p><a class='btn btn-default' href='recipe.php?id=".$relatedRecipe['rid']."'role='button'>View details »</a></p></div>";
                        echo $pageContent;
                    }
                }
            ?>
        </div>
        <div class="review container" style="padding-left: 0px">
            <h2>Reviews</h2>
            <hr>
            <?php
                $reviews = getRecipeReviewById($rid);
                if(empty($reviews)) {
                    echo "<p>Currently no review, be the first one to comment on this</p>";
                }else {
                    foreach ($reviews as $review) {
                        $htmlContent = "<div class='col-lg-4'><h4>".$review['rrtitle']."</h4><hr style='margin-bottom:auto'>";
                        $htmlContent = $htmlContent."<p style='color: grey;'>by ".$review['uname']."</p>";
                        $reviewImg = explode(";", $review['rrimage'])[0];
                        $htmlContent = $htmlContent."<p><img src='".$reviewImg."' class='recipeImg' onerror=\"this.src='../img/default.jpg'\"/>";
                        $rating = $review['rating'];
                        $rateImg = "../img/rate/".$rating.".png";
                        $htmlContent = $htmlContent."<p><img src='".$rateImg."' class='recipeImg' style='width: 50%'/></p>";
                        $htmlContent = $htmlContent."<p>Comment: ".str_replace("\\r\\n", "</br>", $review['rrtext'])."</p>";
                        if(empty($review['suggestion'])){
                            $htmlContent = $htmlContent."</div>";
                        }else {
                            $htmlContent = $htmlContent."<p>Suggestion: ".str_replace("\r\n", "</br>", $review['suggestion'])."</p></div>";
                        }
                        echo $htmlContent;
                    }
                }
            ?>
        </div>
        <div class="comment">
            <h2>Add your comment</h2>
            <hr>
            <form method="post" action="../php/submit_review.php" enctype="multipart/form-data">
                <h3>Title</h3>
                <input maxlength="50" minlength="1" name="title" placeholder="Enter title here" type="text" required class="textInput">
                <input type="hidden" value="<?php echo $rid;?>" name="rid">
                <h3>Comment</h3>
                <textarea maxlength="1000" minlength="1" name="comment" placeholder="Enter comment here" required class="textInput formText"></textarea>
                <h3>Suggestion</h3>
                <input maxlength="255" name="suggestion" placeholder="Enter improve suggestion here" type="text" class="textInput">
                <h3>Rating</h3>
                <select name="rate" class="form-control" style="width: 15%">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
                <br>
                <div id="reviewImg">
                </div>
                <div onclick="addImg()" class="btn btn-primary" style="margin-top:10px;">Add Picture</div>
                <button type="submit" class="btn btn-primary" value="Submit" style="margin-top:10px;">Submit</button>
                <script>
                    function addImg() {
                        var obj = document.getElementById("reviewImg");
                        var newDiv = document.createElement("div");
                        newDiv.innerHTML = "<a href='javascript:' class='a-upload'><input type='file' name='image[]' >Upload Image</a>";
                        obj.appendChild(newDiv);
                    }
                </script>
            </form>
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

