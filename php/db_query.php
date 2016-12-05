<?php
/**
 * Created by PhpStorm.
 * User: cagekira
 * Date: 12/5/16
 * Time: 2:08 AM
 */
require 'db_connect.php';
require 'common_util.php';
/**
 * get all the tags from database
 */
function getTags() {
    $conn = connectDb();
    $sql = "SELECT tname FROM Tag";
    $res = $conn->query($sql);
    $result = $res->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $result;
}

/**
 * get user's uploaded recipes
 * @param $username
 * @return mixed
 */
function getUserRecipe($username) {
    $conn = connectDb();
    $username = cleanInput($username, 32, $conn);
    $sql = "SELECT rid, rtitle, rimage FROM Recipe WHERE uname=? ORDER BY TIMESTAMP";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();
    $result = $res->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $result;
}

/**
 * get user's recently viewed recipes
 * @param $username
 * @return mixed
 */
function getUserViewedRecipe($username) {
    $conn = connectDb();
    $username = cleanInput($username);
    $sql = "SELECT rid, rtitle, rimage FROM Recipe r, Log l WHERE l.uname=? AND l.type=0 AND l.content=r.rid AND l.uname != r.uname ORDER BY l.timestamp";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();
    $result = $res->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $result;
}

function getUserGroup($username) {

}
