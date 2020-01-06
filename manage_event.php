<?php
    require_once("template/boot.php");

    $templateParams["title"] = "Italia's Got Events - Gestisci il tuo Evento";
    $templateParams["name"] = "manage_event_base.php";
    $templateParams["js"] = ["animations.js", "checkErrors.js"];
    if(isset($_GET["id"])){
        $event = $dbh->getEventFromId($_GET["id"]);
    }
    require("template/base.php");
?>