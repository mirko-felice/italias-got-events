<?php
    require_once("template/boot.php");

    session_unset();

    header("location: ".$_SERVER["HTTP_REFERER"]);
?>