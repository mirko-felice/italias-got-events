<?php
    require_once("template/boot.php");

    $evento = $dbh->getEventFromId($_GET["id"]);
    if(!isUserLoggedMe($evento["organizzatore"])){
        $dbh->updateViews($_GET["id"]);
    }
    $templateParams["title"] = "Italia's Got Events - ".$evento["titolo"];
    $templateParams["name"] = "event_base.php";
    $templateParams["js"] = ["animations.js"];
    
    require("template/base.php");
?>