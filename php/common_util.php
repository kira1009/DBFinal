<?php
/**
 * Created by PhpStorm.
 * User: cagekira
 * Date: 11/30/16
 * Time: 3:10 AM
 */
//require "db_query.php";
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

    /**
     * destroy the session
     */
    function sessionDestroy() {
//        session_start();
        session_destroy();
        setcookie(session_name(),'',time()-3600);
        $_SESSION = array();
    }

    /**
     * check the upload images
     * @param $input: $_FILES[""]
     * @param $path: directory of image, coule be "uicon/", "recipeImg/", "reviewImg/" or "eventImg/"
     * @param $username
     */
    function uploadIcon($input, $path, $username, $connection) {
        if (isset($input)) {
            $errors= array();
            $file_size = $input['size'];
            $file_tmp = $input['tmp_name'];
            $file_ext = strtolower(end(explode('.',$input['name'])));
            $extensions= array("jpeg","jpg","png");

            if (in_array($file_ext, $extensions) === false){
                $errors[]="extension not allowed, please choose a JPEG or PNG file.";
            }

            if ($file_size > 2097152) {
                $errors[]='File size must be less than 2 MB';
            }

            if (empty($errors)==true) {
                $newPath = $path . $username . "." . $file_ext;
                move_uploaded_file($file_tmp, $newPath);
                echo "You have successfully uploaded your profile image!";
                return mysqli_real_escape_string($connection, $newPath);
            } else {
                print_r($errors);
            }
        }
        return null;
    }
    function uploadImg($file, $path, $connection) {
        if (empty($file) == false) {
            $errors= array();
            if ($path == '../img/recipeImg/') {
                $maxRid = getMaxRid()[0]['rid'];
                if ($maxRid == NULL) {
                    $count = 0;
                } else {
                    $count = $maxRid + 1;
                }
            } else if ($path == '../img/eventImg/') {
                $maxEid = getMaxErid()[0]['erid'];
                if ($maxEid == NULL) {
                    $count = 0;
                } else {
                    $count = $maxEid + 1;
                }
            } else if ($path == '../img/reviewImg/') {
                $maxRRid = getMaxRRid()[0]['rrid'];
                if ($maxRRid == NULL) {
                    $count = 0;
                } else {
                    $count = $maxRRid + 1;
                }
            } else {
                $errors[] = 'Input path invalid!';
            }

            $result = '';
            $num = 1;
            $file_count = count($file['name']);
//            echo "<script>alert(" . $file_count . ");</script>";
            for ($i = 0; $i < $file_count; $i++) {
                $file_size = $file['size'][$i];
                // check input
                if ($file_size == 0) continue;
                $file_tmp = $file['tmp_name'][$i];
                $file_ext = strtolower(end(explode('.',$file['name'][$i])));
                $extensions= array("jpeg","jpg","png");

                if (in_array($file_ext, $extensions) === false){
                    $errors[]="extension not allowed, please choose a JPEG or PNG file.";
                }

                if ($file_size > 2097152) {
                    $errors[]='File size must be less than 2 MB';
                }

                if (empty($errors) == true) {
                    $newPath = $path . $count . "_" . $num . "." . $file_ext;
                    move_uploaded_file($file_tmp, $newPath);
                    $result = $result . $newPath . ";";
                    $num++;
                }
            }
            if (empty($errors) == true) {
                return mysqli_real_escape_string($connection, $result);
            } else {
                print_r($errors);
            }
        }
        return null;
    }