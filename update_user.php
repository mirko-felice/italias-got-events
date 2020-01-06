<?php
    require_once("template/boot.php");
    if(isUserLoggedIn()){
        $err = "";
        $user = $dbh->getUserFromId($_SESSION["id_utente"]);
        if(!password_verify($_POST["old_password"], $user["password"])){
            $err = "&err_1=1";
        }
        if($_POST["new_password"] !== $_POST["check_password"]){
            $err = $err."&err_2=2";
        }
        if(!$dbh->checkUsername($_POST["username"])){
            $err = $err."&err_3=3";
        }
        if($err !== ""){
            header("location: ".$_SERVER["HTTP_REFERER"].$err);
            die();
        }
        if($_FILES["image"]["name"] !== ""){
            list($result, $msg) = uploadImage(UPLOAD_DIR, $_FILES["image"]);
            if($result != 0){
                $image = $msg;
            } else {
                $image = $user["immagine"];
                $err = "&err=2";
            }
        } else {
            $image = $user["immagine"];
        }
        
        $dbh->updateUser($_POST["email"],  $_POST["new_password"], $_POST["city"], $_POST["phone"], $image, $_POST["username"]);
        $categories = array();
        foreach(array_keys($_POST) as $key){
            if(substr($key, 0, strlen("category_")) === "category_"){
                $category = str_replace("category_", "", $key);
                array_push($categories, $category);
            }
        }
        $dbh->updateUserPreferences(explode(",", $_POST["old_categories"]), $categories);
        header("location: profile.php?id=".$_SESSION["id_utente"].$err);
        die();
    } else {
        header("location: 404.php");
        die();
    }
?>