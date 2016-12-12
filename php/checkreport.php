<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 12/11/16
 * Time: 9:37 PM
 */
session_start();
require "db_query.php";
date_default_timezone_set('America/New_York');

$connection = connectDb();
$response = array();
$response[0] = true;

$thisErid = getMaxErid()[0]['erid'] + 1;
$eid = cleanInput($_POST['eid'], 10, $connection);
$username = $_SESSION['username'];
$ertime = date("Y-m-d H:i:s");
$ertext = cleanInput($_POST['ertext'], 2000, $connection);
$erimage = uploadImg($_FILES['image'], '../img/eventImg/', $connection);

if ($ertext == null) {
    $response[0] = false;
    $response[1] = 'You must enter your report text.';
}

if ($response[0] == true) {
    // insert user's recipe into Recipe table
    if (!($stmt = $connection->prepare("INSERT INTO EventReport (erid, eid, uname, ertime, ertext, erimage) VALUES (?, ?, ?, ?, ?, ?)"))) {
        $response[0] = false;
        $response[1] = 'DB statement prepare error';
        $stmt->close();
        return $response;
    }
    if(!$stmt->bind_param('iissss', $thisErid, $eid, $_SESSION['username'], date("Y-m-d H:i:s"), $ertext, $erimage)) {
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
    $response[1] = "Successfully submit your report!";
    //process return
}
echo "<script>alert('".$response[1]."');</script>";
if($response[0] == true) {
    echo "<script>window.location.href = '../html/home.php';</script>";
}else {
    echo "<script>window.history.go(-1)</script>";
}
?>