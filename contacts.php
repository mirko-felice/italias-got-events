<?php
    require_once("template/boot.php");

    $templateParams["title"] = "Italia's Got Events - Contatti";
    $templateParams["name"] = "contacts_base.php";
    $templateParams["js"] = ["animations.js"];
    $user = $dbh->getUserFromId($_GET["id"]);
    
    require("template/base.php");
?>