<?php
/**
 * Created by PhpStorm.
 * User: cagekira
 * Date: 12/12/16
 * Time: 7:02 PM
 */
require 'db_query.php';

session_start();
date_default_timezone_set('America/New_York');
$conn = connectDb();
$recipeId = cleanInput($_POST['rid'], 10, $conn);
$reviewTitle = cleanInput($_POST['title'], 50, $conn);
$comment = cleanInput($_POST['comment'], 1000, $conn);
$suggestion = cleanInput($_POST['suggestion'], 255, $conn);
$rate = cleanInput($_POST['rate'], 1, $conn);
$username = cleanInput($_SESSION['username'], 32, $conn);


$result = submit_review($conn, $recipeId, $reviewTitle, $comment, $suggestion, $rate, $username);
if($result) {
    $response = "submit successfully!";
}else {
    $response = "Illegal input, please try again";
}

echo "<script>alert('".$response."');</script>";
echo "<script>window.history.go(-1)</script>";

/**
 * insert into RecipeReview the review record
 * @param $conn
 * @param $recipeId
 * @param $reviewTitle
 * @param $comment
 * @param $suggestion
 * @param $rate
 * @param $username
 * @return bool
 */
function submit_review($conn, $recipeId, $reviewTitle, $comment, $suggestion, $rate, $username) {
    if($recipeId == null || $reviewTitle == null || $comment == null || $rate == null || $username == null) {
        return false;
    }
    $imgDir = uploadImg($_FILES['image'], '../img/reviewImg/', $conn);
    if(!($stmt = $conn->prepare("INSERT INTO RecipeReview(rid, uname, rrtime, rrtitle, rrtext, suggestion, rrimage, rating) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"))){
        $stmt->close();
        $conn->close();
        return false;
    }
    if(!($stmt->bind_param('ssssssss',$recipeId, $username, date('Y-m-d H:i:s'), $reviewTitle, $comment, $suggestion, $imgDir, $rate))){
        $stmt->close();
        $conn->close();
        return false;
    }
    if(!$stmt->execute()) {
        $stmt->close();
        $conn->close();
        return false;
    }
    $stmt->close();
    $conn->close();
    return true;
}