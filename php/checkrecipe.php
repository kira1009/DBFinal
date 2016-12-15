<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 12/9/16
 * Time: 1:56 PM
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
<?php
require "db_query.php";
date_default_timezone_set('America/New_York');
$connection = connectDb();
$response = array();
$response[0] = true;
$title = cleanInput($_POST["title"], 255, $connection);
$serving = cleanInput($_POST["serving"], 10, $connection);
$rtext = cleanInput($_POST["step"], 2000, $connection);
$iname = array();
$amount = array();
$unit = array();
$links = array();
$thisRid = getMaxRid()[0]['rid'] + 1;
$rimage = uploadImg($_FILES['image'], '../img/recipeImg/', $connection);
//check and store iname, amount and unit
if (is_array($_POST["iname"])) {
    foreach ($_POST["iname"] as $item) {
        $i = cleanInput($item, 255, $connection);
        if ($i != null) {
            array_push($iname, $i);
        } else {
            $response[0] = false;
            $response[1] = "Illegal Input! Ingredient name invalid!";
        }
    }
} else {
    $response[0] = false;
    $response[1] = "Illegal Input!";
}
if (is_array($_POST["amount"])) {
    foreach ($_POST["amount"] as $item) {
        if ($item != null) {
            array_push($amount, $item);
        } else {
            $response[0] = false;
            $response[1] = "Illegal Input! Amount invalid!";
        }
    }
} else {
    $response[0] = false;
    $response[1] = "Illegal Input! Amount invalid!";
}

if (is_array($_POST["unit"])) {
    foreach ($_POST["unit"] as $item) {
        array_push($unit, $item);
    }
} else {
    $response[0] = false;
    $response[1] = "Illegal Input! Unit invalid!";
}

// check if iname array has duplicate values
if (count($iname) !== count(array_unique($iname))) {
    $response[0] = false;
    $response[1] = "Illegal Input! Ingredient name invalid!";
}

for ($it = 1; $it <= 3; $it++) {
    $ind = 'link' . $it;
    $i = cleanInput($_POST[$ind], 255, $connection);
    if ($i != null) {
        $link = explode("?", $i);
//        echo "<script>alert('" . $link[0] ."');</script>";
//        echo "<script>alert('" . $link[1] ."');</script>";
        if ($link[0] == "localhost/DBFinal/html/recipe.php" or $link[0] == "http://localhost/DBFinal/html/recipe.php") {
            $rid = explode("=", $link[1]);
            if ($rid[0] == 'id' and ctype_digit($rid[1]) and count(getRecipeInfoById($rid[1])) == 1) {
                array_push($links, $rid[1]);
            } else {
                $response[0] = false;
                $response[1] = "Illegal Input! Your related recipe link is invalid1!";
            }
        } else {
            $response[0] = false;
            $response[1] = "Illegal Input! Your related recipe link is invalid2!";
        }

    }
}


if ($response[0] == true) {
    // insert user's recipe into Recipe table
    if (!($stmt = $connection->prepare("INSERT INTO Recipe (uname, rtitle, serving, rtext, rimage, timestamp) VALUES (?, ?, ?, ?, ?, ?)"))) {
        $response[0] = false;
        $response[1] = 'DB statement prepare error';
        $stmt->close();
        return $response;
    }
    if(!$stmt->bind_param('ssisss', $_SESSION['username'], $title, $serving, $rtext, $rimage, date("Y-m-d H:i:s"))) {
        $response[0] = false;
        $response[1] = 'DB parameter bind error';
        $stmt->close();
        return $response;
    }
    if(!$stmt->execute()) {
        $response[0] = false;
        $response[1] = 'DB statement execute error';
        $stmt->close();
        return $response;
    }
    $stmt->close();
    //$_SESSION['username'] = $username;
    $response[0] = true;
    $response[1] = "Successfully submit your recipe!";
    //process return

    // insert user's recipe ingredient into RecipeIngredient table
    $checkIname = array();
    for ($i = 0; $i < count($iname); $i++) {
        if (!($stmt = $connection->prepare("INSERT INTO RecipeIngredient VALUES (?, ?, ?, ?)"))) {
            $response[0] = false;
            $response[1] = 'DB statement prepare error';
            $stmt->close();
            return $response;
        }
        if(!$stmt->bind_param('iiss', $thisRid, $amount[$i], $iname[$i], $unit[$i])) {
            $response[0] = false;
            $response[1] = 'DB parameter bind error';
            $stmt->close();
            return $response;
        }
        if(!$stmt->execute()) {
            $response[0] = false;
            $response[1] = 'DB statement execute error';
            $stmt->close();
            return $response;
        }
        $stmt->close();
        //$_SESSION['username'] = $username;
        $response[0] = true;
        $response[1] = "Successfully submit your recipe!";

        array_push($checkIname, $iname[$i]);
        //process return
    }

    //insert user's recipe tags into RecipeTag table
    $tname = array();
    if (isset($_POST['tags'])) {
        if (is_array($_POST['tags'])) {
            foreach ($_POST['tags'] as $tag) {
                array_push($tname, $tag);
            }
        } else {
            array_push($tname, $_POST['tag']);
        }
    }
    foreach ($tname as $tag) {
        if (!($stmt = $connection->prepare("INSERT INTO RecipeTag VALUES (?, ?)"))) {
            $response[0] = false;
            $response[1] = 'DB statement prepare error';
            $stmt->close();
            return $response;
        }
        if(!$stmt->bind_param('is', $thisRid, $tag)) {
            $response[0] = false;
            $response[1] = 'DB parameter bind error';
            $stmt->close();
            return $response;
        }
        if(!$stmt->execute()) {
            $response[0] = false;
            $response[1] = 'DB statement execute error';
            $stmt->close();
            return $response;
        }
        $stmt->close();
        //$_SESSION['username'] = $username;
        $response[0] = true;
        $response[1] = "Successfully submit your recipe!";
        //process return
    }

    // insert user's related recipe links into RecipeRelation table
    for ($i = 0; $i < count($links); $i++) {
        if (!($stmt = $connection->prepare("INSERT INTO RecipeRelation VALUES (?, ?)"))) {
            $response[0] = false;
            $response[1] = 'DB statement prepare error';
            $stmt->close();
            return $response;
        }
        if(!$stmt->bind_param('ii', $thisRid, $links[$i])) {
            $response[0] = false;
            $response[1] = 'DB parameter bind error';
            $stmt->close();
            return $response;
        }
        if(!$stmt->execute()) {
            $response[0] = false;
            $response[1] = 'DB statement execute error';
            $stmt->close();
            return $response;
        }
        $stmt->close();
        $response[0] = true;
        $response[1] = "Successfully submit your recipe!";

        //process return
    }
}


echo "<script>alert('".$response[1]."');</script>";
if($response[0] == true) {
    echo "<script>window.location.href = '../html/home.php';</script>";
}else {
    echo "<script>window.history.go(-1)</script>";
}
?>
<body>
</body>
</html>
