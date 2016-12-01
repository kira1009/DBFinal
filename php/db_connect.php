<?php
/**
 * Created by PhpStorm.
 * User: cagekira
 * Date: 11/29/16
 * Time: 12:13 AM
 */

require 'config.inc';

    /**
     * connect to database
     * @return mysqli|null
     */
    function connectDb() {
        $connection = new mysqli($dbhost, $userName, $passwd, $schema, $port);
        if ($connection->connect_errno) {
            echo "Failed to connect to MySQL: (" . $connection->connect_errno . ") " . $connection->connect_error;
            return null;
        }
        return $connection;
}
