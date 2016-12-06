<?php
/**
 * Created by PhpStorm.
 * User: cagekira
 * Date: 12/5/16
 * Time: 2:08 AM
 */
require 'db_connect.php';
require 'common_util.php';

//var_dump(getUserGroupButNoRsvpEvent("kira1009"));
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
    $username = cleanInput($username, 32, $conn);
    $sql = "SELECT rid, rtitle, rimage FROM Recipe r, Userlogs l WHERE l.uname=? AND l.type=0 AND l.content=r.rid AND l.uname != r.uname ORDER BY l.time";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();
    $result = $res->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $result;
}

/**
 * @param $username
 * @return mixed
 */
function getUserGroup($username) {
    $conn = connectDb();
    $username = cleanInput($username, 32, $conn);
    $sql = "SELECT g.gname from Groups g, GroupMember gm WHERE g.gid = gm.gid AND gm.uname=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();
    $result = $res->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $result;
}

/**
 * get user rsvped events
 * @param $usrname
 */
function getRsvpEvent($username) {
    $conn = connectDb();
    $username = cleanInput($username, 32, $conn);
    $sql = "SELECT etitle, er.eid FROM EventRSVP er, Events e WHERE er.uname=? ORDER BY etime";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();
    $result = $res->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $result;
}

/**
 * get user not rsvped but in usr's group events
 * @param $usrname
 */
function getUserGroupButNoRsvpEvent($username) {
    $conn = connectDb();
    $username = cleanInput($username, 32, $conn);
    $sql = "SELECT e.eid, e.etitle FROM Events e, Groups g, GroupMember gm WHERE e.gid = g.gid AND g.gid = gm.gid AND gm.uname = ? AND e.eid NOT IN (SELECT eid FROM EventRSVP WHERE uname = ?) ORDER BY e.etime";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $username, $username);
    $stmt->execute();
    $res = $stmt->get_result();
    $result = $res->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $result;
}
