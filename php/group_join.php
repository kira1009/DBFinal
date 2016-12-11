<?php
/**
 * Created by PhpStorm.
 * User: cagekira
 * Date: 12/11/16
 * Time: 1:00 AM
 */
require 'db_connect.php';
require 'common_util.php';

session_start();
$gId = $_GET['id'];
$username = $_SESSION['username'];
$conn = connectDb();
$username = cleanInput($username, 32, $conn);
$gId = cleanInput($gId, 10, $conn);
$sql = "INSERT INTO GroupMember VALUES (?,?)";
$stmt = $conn->prepare($sql);
$response = true;
if(!$stmt->bind_param('ss', $gId, $username)) {
    $response = false;
    $stmt->close();
}
if(!$stmt->execute()) {
    $response = false;
    $stmt->close();
}

if($response == false) {
    echo "<script>alert('illegal operation, please try again');</script>";
}
echo "<script>window.history.go(-1)</script>";
?>