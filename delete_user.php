<?php
    require_once("template/boot.php");
    if(isUserLoggedIn() && $_SESSION["id_utente"] == $dbh->getRootId()){
        $dbh->deleteUser($_GET["id"]);
        header("location: profile.php?id=".$_GET["id"]);
        die();
    } else {
        header("location: 404.php");
        die();
    }
?>