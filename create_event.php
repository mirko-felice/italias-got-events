<?php
    require_once("template/boot.php");
    if(!isUserLoggedIn()){
        header("location: 404.php");
        die();
    } else {
        $err = "";
        $image = "";
        if($_FILES["image"]["name"] !== ""){
            list($result, $msg) = uploadImage(UPLOAD_DIR, $_FILES["image"]);
            if($result != 0){
                $image = $msg;
            } else {
                $err = "&err=1";
            }
        }
        if(!isset($_POST["check_more_days"])){
            $_POST["end_date"] = null;
        }
        $_POST["time"] = $_POST["time"]."-00";
        $id = $dbh->addEvent($_POST["title"], strtolower($_POST["city"]), $_POST["address"], $image, $_POST["start_date"], $_POST["end_date"], $_POST["category"], $_POST["long_desc"], $_POST["price"], $_POST["time"]);
        $dbh->addTicketsForEvent($id, $_POST["number_tickets"]);
        header("location: event.php?id=".$id.$err);
        die();
    }
?>