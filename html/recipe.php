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
                    foreach ($ingredients as $ingredient) {
                        $amount = $ingredient['amount'];
                        $unit = $ingredient['unit'];
                        $name = $ingredient['iname'];
                        echo "<p>".$amount." ".$unit." of ".$name."</p>";
                    }
                ?>
            </div>
            <div id="slider" class="nivoSlider">
                <?php
                    $images = $basicInfo[0]['rimage'];
                    $imgDirs = explode(';', $images);
                    foreach ($imgDirs as $imgDir) {
                        echo "<img src='".$imgDir."' alt='no image' onerror=\"this.src='../img/default.jpg'\"/>";
                    }
                ?>
            </div>
        </div>
        <div class="description">
            <h2>Description</h2>
            <hr>
            <p>
                <?php echo $basicInfo[0]['rtext'];?>
            </p>
        </div>
        <div class="review">
            <h2>Reviews</h2>
            <hr>
            <?php
                $reviews = getRecipeReviewById($rid);
                if(empty($reviews)) {
                    echo "<p>Currently no review, be the first one to comment on this</p>";
                }else {
                    foreach ($reviews as $review) {
                        $htmlContent = "<div class='col-lg-4'><h4>".$review['rrtitle']."</h4>";
                    }
                }
            ?>
        </div>
        <div class="comment">
            <h2>Add your comment</h2>
            <hr>
            <form method="post" action="../php/submit_review.php" enctype="multipart/form-data">
                <input autofocus="autofocus" maxlength="32" minlength="1" name="title" placeholder="Username" type="text" required class="formInput">
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

