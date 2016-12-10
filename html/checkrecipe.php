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
require "../php/db_query.php";
$connection = connectDb();
$response = array();
$title = cleanInput($_POST["title"], 255, $connection);
$serving = cleanInput($_POST["serving"], 10, $connection);
$rtext = cleanInput($_POST["step"], 2000, $connection);
$ingredient = array();
$iname = array();
$amount = array();
$unit = array();
$rimage = uploadImg($_FILES['image'], '../img/recipeImg/', $connection);
if (is_array($_POST["iname"])) {
    foreach ($_POST["iname"] as $item) {
        $i = cleanInput($item, 255, $connection);
        if ($i != null) {
            array_push($iname, $i);
        } else {
            $response[0] = false;
            $response[1] = "Illegal Input!";
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
            $response[1] = "Illegal Input!";
        }
    }
} else {
    $response[0] = false;
    $response[1] = "Illegal Input!";
}

if (is_array($_POST["unit"])) {
    foreach ($_POST["unit"] as $item) {
        array_push($unit, $item);
    }
} else {
    $response[0] = false;
    $response[1] = "Illegal Input!";
}
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
