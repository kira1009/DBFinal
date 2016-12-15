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
    $username = cleanInput($username, 32, $conn);
    $sql = "SELECT DISTINCT rid, rtitle, rimage FROM Recipe r, Userlogs l WHERE l.uname=? AND l.type=0 AND l.content=r.rid AND l.uname != r.uname ORDER BY l.time";
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
    $sql = "SELECT g.gid, g.gname, g.description from Groups g, GroupMember gm WHERE g.gid = gm.gid AND gm.uname=?";
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
    $sql = "SELECT etitle, er.eid FROM EventRSVP er, Events e WHERE er.uname=? and er.eid = e.eid ORDER BY etime";
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
 * @param $username
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

/**
 * get group users by group id
 * @param $groupId
 * @return mixed
 */
function getGroupUsersById($groupId) {
    $conn = connectDb();
    $groupId = cleanInput($groupId, 10, $conn);
    $sql = "SELECT uname FROM GroupMember WHERE gid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $groupId);
    $stmt->execute();
    $res = $stmt->get_result();
    $result = $res->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $result;
}

/**
 * check whether a user has a group
 * @param $username
 */
function hasAGroup($username) {
    $conn = connectDb();
    $username = cleanInput($username, 32, $conn);
    $sql = "SELECT count(*) as count FROM GroupMember WHERE uname = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();
    $result = $res->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    $groupCount = $result[0]['count'];
    if($groupCount < 1) {
        return false;
    }
    return true;
}

/**
 * get recipes for the query
 * @param $keyword
 * @param $tags
 * @return array
 */
function getRecipeByKeywordTags($keyword, $tags) {
    $keywordRes = getRecipeByKeyword($keyword);
    $tagRes = getRecipeByTags($tags);
    $record = [];
    $result = [];
    foreach ($keywordRes as $tmpElement) {
        $rid = $tmpElement['rid'];
        if(in_array($rid, $record)){
            continue;
        }else {
            array_push($record, $rid);
            array_push($result, $tmpElement);
        }
    }

    foreach ($tagRes as $tmpElement) {
        $rid = $tmpElement['rid'];
        if(in_array($rid, $record)){
            continue;
        }else {
            array_push($record, $rid);
            array_push($result, $tmpElement);
        }
    }

    return $result;
}

/**
 * get recipe information by tags
 * @param $tags
 * @return mixed|null
 */
function getRecipeByTags($tags) {
    $conn = connectDb();

    if(empty($tags)) {
        return [];
    }else {
        $sql = "SELECT DISTINCT r.rid, r.uname, r.rtitle, r.rimage FROM RecipeTag rt, Recipe r WHERE rt.rid = r.rid AND tname in ('".$tags[0]."'";
        $size = count($tags);
        $seq = 1;
        while($seq < $size) {
            $sql = $sql.","."'".$tags[$seq]."'";
            $seq++;
        }
        $sql = $sql.")";
        $res = $conn->query($sql);
        $result = $res->fetch_all(MYSQLI_ASSOC);
        $conn->close();
        return $result;
    }
}

/**
 * get recipe information by keyword
 * @param $keyword
 * @return array
 */
function getRecipeByKeyword($keyword) {
    $keyArr = explode(" ", $keyword);
    $conn = connectDb();
    $resultArr = [];
    foreach($keyArr as $key) {
        $key = cleanInput($key, 50, $conn);
        $sql = "SELECT rid, uname, rtitle, rimage FROM Recipe WHERE rtitle LIKE ? OR rtext LIKE ?";
        $key = "%".$key."%";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $key, $key);
        $stmt->execute();
        $res = $stmt->get_result();
        $result = $res->fetch_all(MYSQLI_ASSOC);
        $resultArr = array_merge($resultArr, $result);
    }
    $conn->close();
    return $resultArr;
}

function getMaxRid() {
    $conn = connectDb();
    $res = mysqli_query($conn, "SELECT MAX(rid) AS rid FROM Recipe");
    $conn->close();
    $result = $res->fetch_all(MYSQLI_ASSOC);
    return $result;
}

function getMaxEid() {
    $conn = connectDb();
    $res = mysqli_query($conn, "SELECT MAX(eid) AS eid FROM Events");
    $conn->close();
    $result = $res->fetch_all(MYSQLI_ASSOC);
    return $result;
}

function getMaxRRid() {
    $conn = connectDb();
    $res = mysqli_query($conn, "SELECT MAX(rrid) AS rrid FROM RecipeReview");
    $conn->close();
    $result = $res->fetch_all(MYSQLI_ASSOC);
    return $result;
}

function getMaxGid() {
    $conn = connectDb();
    $res = mysqli_query($conn, "SELECT MAX(gid) AS gid FROM Groups");
    $conn->close();
    $result = $res->fetch_all(MYSQLI_ASSOC);
    return $result;
}

function getMaxErid() {
    $conn = connectDb();
    $res = mysqli_query($conn, "SELECT MAX(erid) AS erid FROM EventReport");
    $conn->close();
    $result = $res->fetch_all(MYSQLI_ASSOC);
    return $result;
}

/**
 * get the detail of a recipe by its id which includes
 * the recipe's title, description, creator, serving info, images, ingredients, reviews
 * maybe not useful
 * 0-basic info
 * 1-ingredients
 * 2-tags
 * 3-reviewImg
 * @param $rid
 * @return mixed
 */
function getRecipeDetailById($rid) {
    $conn = connectDb();
    $conn->close();
    $recipeInfo = getRecipeInfoById($rid);
    $ingredientInfo = getRecipeIngredientById($rid);
    $recipeReview = getRecipeReviewById($rid);
    $recipeTag = getRecipeTagById($rid);
    $result[0] = $recipeInfo;
    $result[1] = $ingredientInfo;
    $result[2] = $recipeTag;
    $result[3] = $recipeReview;
    return $result;
}

/**
 * get recipe's creator name, titile, number of serving, description, images and created time.
 * @param $rid
 * @return mixed
 */
function getRecipeInfoById($rid) {
    $conn = connectDb();
    $rid = cleanInput($rid, 10, $conn);
    $sql = "SELECT uname, rtitle, serving, rtext, rimage, timestamp FROM Recipe WHERE rid=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $rid);
    $stmt->execute();
    $res = $stmt->get_result();
    $result = $res->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $result;
}

/**
 * get the ingredients to make this recipe
 * @param $rid
 * @return mixed
 */
function getRecipeIngredientById($rid) {
    $conn = connectDb();
    $rid = cleanInput($rid, 10, $conn);
    $sql = "SELECT amount, unit, iname FROM RecipeIngredient WHERE rid=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $rid);
    $stmt->execute();
    $res = $stmt->get_result();
    $result = $res->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $result;
}

/**
 * get the recipe's tags
 * @param $rid
 * @return mixed
 */
function getRecipeTagById($rid) {
    $conn = connectDb();
    $rid = cleanInput($rid, 10, $conn);
    $sql = "SELECT tname FROM RecipeTag WHERE rid=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $rid);
    $stmt->execute();
    $res = $stmt->get_result();
    $result = $res->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $result;
}

/**
 * get recipe's comment
 * @param $rid
 * @return mixed
 */
function getRecipeReviewById($rid) {
    $conn = connectDb();
    $rid = cleanInput($rid, 10, $conn);
    $sql = "SELECT uname, rrtime, rrtitle, rrtext, suggestion, rrimage, rating FROM RecipeReview WHERE rid=? ORDER BY rrtime";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $rid);
    $stmt->execute();
    $res = $stmt->get_result();
    $result = $res->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $result;
}

/**
 * find all the groups that user did not join
 * @param $username
 * @return mixed
 */
function findNotJoinedGroup($username) {
    $conn = connectDb();
    $username = cleanInput($username, 32, $conn);
    $sql = "SELECT gid, gname, description FROM Groups WHERE gid NOT IN (SELECT gid FROM GroupMember WHERE uname=?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();
    $result = $res->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $result;
}

/**
 * check if user is in this group
 * @param $username
 * @param $gid
 * @return true or false
 */
function isMember($gid, $username) {
    $conn = connectDb();
    $username = cleanInput($username, 32, $conn);
    $sql = "SELECT count(*) as count FROM GroupMember WHERE  gid = ? and uname = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $gid, $username);
    $stmt->execute();
    $res = $stmt->get_result();
    $result = $res->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    $groupCount = $result[0]['count'];
    if($groupCount == 1) {
        return true;
    }
    return false;
}

/**
 * check if user RSVP this event
 * @param $eid
 * @param $username
 * @return true or false
 */
function isRSVP($eid, $username) {
    $conn = connectDb();
    $username = cleanInput($username, 32, $conn);
    $sql = "SELECT count(*) as count FROM EventRSVP WHERE  eid = ? and uname = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $eid, $username);
    $stmt->execute();
    $res = $stmt->get_result();
    $result = $res->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    $groupCount = $result[0]['count'];
    if($groupCount == 1) {
        return true;
    }
    return false;
}

/**
 * get info of a event
 * @param $eid
 * @return array of event
 */
function getEventInfoByEid($eid) {
    $conn = connectDb();
    $sql = "SELECT * FROM Events WHERE eid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $eid);
    $stmt->execute();
    $res = $stmt->get_result();
    $result = $res->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $result;
}

/**
 * get all reports for this event
 * @param $eid
 * @return array of results
 */
function getReportInfoByEid($eid) {
    $conn = connectDb();
    $sql = "SELECT * FROM EventReport WHERE eid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $eid);
    $stmt->execute();
    $res = $stmt->get_result();
    $result = $res->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $result;
}

/**
 * record user activities
 * @param $type
 * @param $content
 * @param $time
 * @param $username
 */
function record_usr_behavior($type, $content, $time, $username){
    $conn = connectDb();
    $sql = "INSERT INTO Userlogs(uname, type, content, time) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss', $username, $type, $content, $time);
    $stmt->execute();
    $conn->close();
    return true;
}

/**
 * get all related recipes
 * @param $rid
 * @return array of related rid
 */
function getRelatedRid($rid) {
    $conn = connectDb();
    $sql = "SELECT r.rid, r.rtitle, r.uname, r.rimage FROM RecipeRelation rr, Recipe r WHERE rr.relateTo = r.rid AND rr.rid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $rid);
    $stmt->execute();
    $res = $stmt->get_result();
    $result = $res->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $result;
}

/**
 * get all RSVPed user for an event
 * @param $eid
 * @return array of RSVPed users
 */
function getRSVPedUser($eid) {
    $conn = connectDb();
    $sql = "SELECT uname FROM EventRSVP WHERE eid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $eid);
    $stmt->execute();
    $res = $stmt->get_result();
    $result = $res->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $result;
}

/**
 * get user's profile by the username
 * @param $uname
 */
function getUserProfile($name) {
    $conn = connectDb();
    $name = cleanInput($name, 32, $conn);
    $sql = "SELECT uname, realname, uprofile, uicon From User WHERE uname=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $name);
    $stmt->execute();
    $res = $stmt->get_result();
    $result = $res->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $result[0];
}