<?php
    require_once("template/boot.php");
    if(!isUserLoggedIn()){
        header("location: 404.php");
        die();
    } else {
        $err = "";
        $event = $dbh->getEventFromId($_POST["id_evento"]);
        if($_FILES["image"]["name"] !== ""){
            list($result, $msg) = uploadImage(UPLOAD_DIR, $_FILES["image"]);
            if($result != 0){
                $image = $msg;
            } else {
                $image = $event["immagine_evento"];
                $err = "&err=2";
            }
        } else {
            $image = $event["immagine_evento"];
        }
        if(!isset($_POST["check_more_days"])){
            $_POST["end_date"] = null;
        }
        $_POST["time"] = $_POST["time"]."-00";
        $dbh->updateEvent($_POST["id_evento"], $_POST["category"], $_POST["title"], strtolower($_POST["city"]), $_POST["address"], $_POST["start_date"], $_POST["end_date"], $_POST["time"], $_POST["long_desc"], $image);
        header("location: event.php?id=".$_POST["id_evento"].$err);
        die();
    }
?>