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
    function uploadImage($input, $path, $username, $connection) {
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