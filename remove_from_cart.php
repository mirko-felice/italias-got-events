<?php
    require_once("template/boot.php");
    if(!isUserLoggedIn()){
        header("location: 404.php");
        die();
    } else {
        if(($_COOKIE[$_GET["id_evento"]]) - $_GET["number"] == 0){
            setcookie($_GET["id_evento"], "", time() - 100, "/");
        } else {
            setcookie($_GET["id_evento"], ($_COOKIE[$_GET["id_evento"]]) - $_GET["number"], time() + (60 * 60 * 24 * 30), "/");
        }
        header("location: cart.php");
        die();
    }
?>