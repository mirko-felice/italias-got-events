<?php
    require_once("template/boot.php");

    $templateParams["title"] = "Italia's Got Events - Gestisci il tuo profilo";
    $templateParams["name"] = "manage_profile_base.php";
    $templateParams["js"] = ["animations.js"];
    if(!isset($_GET["id"]) || !isUserLoggedIn()){
        header("location: 404.php");
        die();
    }
    $user = $dbh->getUserFromId($_GET["id"]);
    if(!isset($user["categorie"])){
        $user["categorie"] = array();
    }

    require("template/base.php");
?>