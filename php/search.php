<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 12/6/16
 * Time: 7:18 PM
 */

$connection = connectDb();
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: (" . mysqli_connect_errno() . ") " . mysqli_connect_error();
    $connection = null;
    $response[0] = false;
    $response[1] = 'Database connection error';
} else {
    $response = searchRecipe($connection);
}

function searchRecipe($connection) {
    $search_text = cleanInput($_POST["search"], 255, $connection);
    if($search_text == null) {
        $response[0] = false;
        $response[1] = 'Illegal search text';
        return $response;
    }

    //check username duplication
    if (!($stmt0 = $connection->prepare("select rid from Recipe where rtitle like ?"))) {
        $response[0] = false;
        $response[1] = 'DB statement prepare error';
        $stmt0->close();
        return $response;
    }

    if(!$stmt0->bind_param('s', "'%" . $search_text . "%'")) {
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
    $response[1] = $row;
    $stmt0->close();
    return $response;
}
