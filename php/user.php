<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" href="../css/bootstrap.min.css" type="text/css"/>
</head>
<body>
<?php

require 'db_connect.php';
require 'common_util.php';

//initialize DB connection
$connection = connectDb();
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: (" . mysqli_connect_errno() . ") " . mysqli_connect_error();
    $connection = null;
}

// 1-register, 0-login
if($_POST['origin'] == '1') {
    $response = register_user($connection);
}else {
    //user login
    $response = user_login($connection);
}

//process return
echo "<script>alert('".$response[1]."');</script>";
if($response[0] == true) {
    echo "<script>window.location.href = '../html/home.php';</script>";
}else {
    echo "<script>window.history.go(-1)</script>";
}

function register_user($connection) {
    if($connection == null) {
        $response[0] = false;
        $response[1] = 'Database connection error';
        return;
    }

    //check whether the input is valid
    $username = cleanInput($_POST['username'], 32, $connection);
    $name = cleanInput($_POST['realname'], 255, $connection);
    $password = cleanInput($_POST['password'], 255, $connection);
    $profile = cleanInput($_POST['profile'], 255, $connection);

    if($username == null || $name == null || $password == null) {
        $response[0] = false;
        $response[1] = 'Illegal input, go back to register page';
        return $response;
    }

    //check username duplication
    if (!($stmt0 = $connection->prepare("SELECT COUNT(*) FROM User WHERE uname=?"))) {
        $response[0] = false;
        $response[1] = 'DB statement prepare error';
        $stmt0->close();
        return $response;
    }

    if(!$stmt0->bind_param('s', $username)) {
        $response[0] = false;
        $response[1] = 'DB parameter bind error';
        $stmt0->close();
        return $response;
    }

    if(!$stmt0->execute()) {
        $response[0] = false;
        $response[1] = 'DB statement execute error';
        $stmt0->close();
        return $response;
    }

    $res = $stmt0->get_result();
    $row = $res->fetch_array(MYSQLI_NUM);

    if($row[0] >= 1) {
        $response[0] = false;
        $response[1] = 'The username has been used, please change another one';
        $stmt0->close();
        return $response;
    }
    $stmt0->close();

    // insert user's information into user table
    if (!($stmt = $connection->prepare("INSERT INTO User VALUES (?, ?, ?, ?, NULL)"))) {
        $response[0] = false;
        $response[1] = 'DB statement prepare error';
        $stmt->close();
        return $response;
    }
    if(!$stmt->bind_param('ssss', $username, $name, $profile, md5($password))) {
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
    $response[1] = "Successfully registered";
    return $response;
}

?>
</body>
</html>
