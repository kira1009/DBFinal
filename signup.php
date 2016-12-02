<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 12/1/16
 * Time: 7:02 PM
 */
$username = isset($_POST["username"]) ? htmlspecialchars($_POST["username"]) : NULL;
$realname = isset($_POST["realname"]) ? htmlspecialchars($_POST["realname"]) : NULL;
$password = isset($_POST["password"]) ? htmlspecialchars($_POST["password"]) : NULL;
$profile = isset($_POST["profile"]) ? htmlspecialchars($_POST["profile"]) : NULL;

if ($username == NULL || !ctype_alnum($username)) {
    echo "You should type your username correctly! ";
    die ("<a href='signup.html'>Go back</a>");
}
if ($password == NULL) {
    echo "You should type your password! ";
    die ("<a href='signup.html'>Go back</a>");
}
$conn = mysqli_connect("52.36.21.186", "cookzillaadmin", "Cook4zilla", "CookZilla");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$query = "INSERT INTO User VALUES ('" . $username . "','" . $realname . "','" .
    $profile . "','" . md5($password) ."'," . "NULL)";
//echo $query;
if (mysqli_query($conn, $query)) {
    echo "Registered successfully!";
} else {
    echo "Unsuccessful!";
    die ("<a href='signup.html'>Go back</a>");
};

mysqli_close($conn);
?>


