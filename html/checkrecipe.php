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
include_once "../php/common_util.php";
//require "../php/db_connect.php";
include_once "../php/db_query.php";
$connection = connectDb();
$response = array();
$title = cleanInput($_POST["title"], 255, $connection);
$serving = cleanInput($_POST["serving"], 10, $connection);
$rtext = cleanInput($_POST["step"], 2000, $connection);
$ingredient = array();
$iname = array();
$amount = array();
$unit = array();
$image = array();
foreach ($_POST["iname"] as $item) {
    $i = cleanInput($item, 255, $connection);
    if ($i == null) {
        $response[0] = false;
        $response[1] = "Illegal Input!";
    } else {
        array_push($iname, $i);
    }

}
foreach ($_POST["amount"] as $item) {
    if ($item == null) {
        $response[0] = false;
        $response[1] = "Illegal Input!";
    } else {
        array_push($iname, $item);
    }
}
foreach ($_POST["unit"] as $item) {
    if ($item == null) {
        $response[0] = false;
        $response[1] = "Illegal Input!";
    } else {
        array_push($unit, $item);
    }
}
foreach ($_FILES["image"] as $item) {
    if ($item == null) {
        $response[0] = false;
        $response[1] = "Illegal Input!";
    } else {
        array_push($unit, $item);
    }
}
?>
<body>
<?php //$result = getMaxRid(); while ($row = $result->fetch_assoc()) {echo "<h1>" . $row['rid'] . "</h1>";} ?>
<p><?= $title ?></p>
<p><?= $response[0] . $response[1]?></p>
<p><?= $iname[0] . "+" . $iname[1]."+".$iname[2] ?></p>
<p><?= $amount[0]."+".$amount[1]."+".$amount[2] ?></p>
<p><?= $unit[0]."+".$unit[1]."+".$unit[2]?></p>
</body>
</html>
