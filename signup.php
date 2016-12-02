<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 12/1/16
 * Time: 7:02 PM
 */
$username = $_POST["username"];
$realname = $_POST["realname"];
$password = $_POST["password"];
$profile = $_POST["profile"];
$conn = mysqli_connect("52.36.21.186", "cookzillaadmin", "Cook4zilla", "CookZilla");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$query = "INSERT INTO User VALUES ('" . $username . "','" . $realname . "','" .
    $profile . "','" . md5($password) ."'," . "NULL)";
echo $query;
mysqli_query($conn, $query);
echo "Registered successfully!";
mysqli_close($conn);
?>