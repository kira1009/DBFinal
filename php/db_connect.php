<?php
/**
 * Created by PhpStorm.
 * User: cagekira
 * Date: 11/29/16
 * Time: 12:13 AM
 */


    /**
     * connect to database
     * @return mysqli|null
     */
    function connectDb() {
        $dbhost = "52.36.21.186";
        $userName = "cookzillaadmin";
        $passwd = "Cook4zilla";
        $schema = "CookZilla";
        $connection = new mysqli($dbhost, $userName, $passwd, $schema);
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: (" . mysqli_connect_errno() . ") " . mysqli_connect_error();
            return null;
        }
        return $connection;
    }
