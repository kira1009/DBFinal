<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 12/11/16
 * Time: 1:39 AM
 */
session_start();
?>

<?php
require "db_query.php";
$connection = connectDb();
$response = array();
$response[0] = true;
$gname = cleanInput($_POST['gname'], 50, $connection);
$description = cleanInput($_POST['description'], 255, $connection);
$thisGid = getMaxGid()[0]['gid'] + 1;
if ($gname == null) {
    $response[0] = false;
    $response[1] = "You must input your group name!";
}
if ($response[0] == true) {
    // insert group to Groups table
    if (!($stmt = $connection->prepare("INSERT INTO Groups (gname, description) VALUES (?, ?)"))) {
        $response[0] = false;
        $response[1] = 'DB statement prepare error';
        $stmt->close();
        return $response;
    }
    if (!$stmt->bind_param('ss', $gname, $description)) {
        $response[0] = false;
        $response[1] = 'DB parameter bind error';
        $stmt->close();
        return $response;
    }
    if (!$stmt->execute()) {
        $response[0] = false;
        $response[1] = 'DB statement execute error';
        $stmt->close();
        return $response;
    }
    $stmt->close();
    //$_SESSION['username'] = $username;
    $response[0] = true;
    $response[1] = "Successfully create your group!";
    //process return
}

if ($response[0] == true) {
    // insert user to GroupMember table
    if (!($stmt = $connection->prepare("INSERT INTO GroupMember (gid, uname) VALUES (?, ?)"))) {
        $response[0] = false;
        $response[1] = 'DB statement prepare error';
        $stmt->close();
        return $response;
    }
    if(!$stmt->bind_param('is', $thisGid, $_SESSION['username'])) {
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
    $response[1] = "Successfully create your group!";
    //process return
}

echo "<script>alert('".$response[1]."');</script>";
if($response[0] == true) {
    echo "<script>window.location.href = '../html/home.php';</script>";
}else {
    echo "<script>window.history.go(-1)</script>";
}
?>