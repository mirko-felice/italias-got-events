<?php
    require_once("template/boot.php");

    $templateParams["title"] = "Italia's Got Events - Organizzatori";
    $templateParams["name"] = "managers_list.php";
    $templateParams["js"] = ["animations.js"];
    $templateParams["organizators"] = $dbh->getManagers();

    require("template/base.php");
?>