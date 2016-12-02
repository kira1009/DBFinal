<?php
/**
 * Created by PhpStorm.
 * User: cagekira
 * Date: 11/30/16
 * Time: 3:10 AM
 */

    /**
     * check whether the input is illegal to avoid sql injection or sql attack
     * and check the XXS attack for the input
     * @param $input
     * @param $maxLength
     * @param $connection
     * @return null|string
     */
    function cleanInput($input, $maxLength, $connection) {
        if(isset($input)) {
            $result = substr($input, 0, $maxLength);
            $result = mysqli_real_escape_string($connection, $result);
            $result = htmlspecialchars($result);
            return ($result);
        }
        return null;
    }