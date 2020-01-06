<?php
    require_once("template/boot.php");
    if(!isUserLoggedIn()){
        header("location: 404.php");
        die();
    } else {
        $dbh->enableEvent($_GET["id"]);
        if($_SESSION["id_utente"] == $dbh->getRootId()){
            header("location: event.php?id=".$_GET["id"]);
            die();
        }
    }
?>