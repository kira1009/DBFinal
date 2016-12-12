<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 12/11/16
 * Time: 3:19 PM
 */
session_start();
?>

<?php
require "db_query.php";
date_default_timezone_set('America/New_York');

$connection = connectDb();
$response = array();
$response[0] = true;
$thisEid = getMaxEid()[0]['eid'] + 1;
$gid = $_POST['gid'];
$etitle = cleanInput($_POST['etitle'], 255, $connection);
$edesc = cleanInput($_POST['edesc'], 2000, $connection);
$elocation = cleanInput($_POST['elocation'], 255, $connection);
$etime = date("Y-m-d H:i:s", strtotime($_POST['etime']));

// check if user's input is valid
if ($etime == "1969-12-31 19:00:00") {
    $response[0] = false;
    $response[1] = "You must input event\'s time!";
}

if ($etitle == null) {
    $response[0] = false;
    $response[1] = "You must input your event\'s title!";
}

// insert user's event into Events table
if ($response[0] == true) {
    if (!($stmt = $connection->prepare("INSERT INTO Events (eid, gid, etitle, edesc, elocation, etime) VALUES (?, ?, ?, ?, ?, ?)"))) {
        $response[0] = false;
        $response[1] = 'DB statement prepare error';
        $stmt->close();
        return $response;
    }
    if(!$stmt->bind_param('iissss', $thisEid, $gid, $etitle, $edesc, $elocation, $etime)) {
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
    $response[1] = "Successfully create your event!";
    //process return
}

// insert user into EventRSVP table
if ($response[0] == true) {
    if (!($stmt = $connection->prepare("INSERT INTO EventRSVP (eid, uname) VALUES (?, ?)"))) {
        $response[0] = false;
        $response[1] = 'DB statement prepare error';
        $stmt->close();
        return $response;
    }
    if(!$stmt->bind_param('is', $thisEid, $_SESSION['username'])) {
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
    $response[1] = "Successfully create your event!";
    //process return
}

echo "<script>alert('".$response[1]."');</script>";
if($response[0] == true) {
    echo "<script>window.location.href = '../html/home.php';</script>";
}else {
    echo "<script>window.history.go(-1)</script>";
}
?>