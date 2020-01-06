<?php
    require_once("template/boot.php");
    $result = 0;
    if($_FILES["image"]["name"] !== ""){
        list($result, $msg) = uploadImage(UPLOAD_DIR, $_FILES["image"]);
    }
    $err = "";
    if($result != 0){
        $image = $msg;
    } else {
        $image = "";
        $err = "&err=1";
    }
    $id = $dbh->registerUser($_POST["username"], $_POST["password"], $_POST["email"], $_POST["name"], $_POST["surname"], $_POST["dateofbirth"], $_POST["phone"], $_POST["city"], $image);

    $categories = array();
    foreach(array_keys($_POST) as $key){
        if(substr($key, 0, strlen("category_")) === "category_"){
            $category = str_replace("category_", "", $key);
            array_push($categories, $category);
        }
    }
    $dbh->addUserPreferences($id, $categories);
    header("location: index.php");
    die();
?>