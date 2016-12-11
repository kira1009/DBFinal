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
$thisRid = getMaxRid()[0]['rid'] + 1;
$rimage = uploadImg($_FILES['image'], '../img/recipeImg/', $connection);
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

// insert user's recipe into Recipe table
if ($response[0] == true) {
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
