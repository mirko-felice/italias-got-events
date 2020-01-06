<?php
    require_once("template/boot.php");

    $user = $dbh->getUserFromId($_GET["id"]);
    if(empty($user)){
        header("location: 404.php");
        die();
    }
    $templateParams["title"] = "Italia's Got Events - Profilo di ".$user["nome"]." ".$user["cognome"];
    $templateParams["name"] = "profile_base.php";
    $templateParams["js"] = ["animations.js"];
    
    require("template/base.php");
?>