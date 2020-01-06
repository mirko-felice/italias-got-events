<?php
    require_once("template/boot.php");
    if(!isUserLoggedIn()){
        header("location: 404.php");
        die();
    } else {
        if(isset($_COOKIE[$_GET["id_evento"]])){
            setcookie($_GET["id_evento"], ($_COOKIE[$_GET["id_evento"]]) + $_GET["number"], time() + (60 * 60 * 24 * 30), "/");
        } else {
            setcookie($_GET["id_evento"], $_GET["number"], time() + (60 * 60 * 24 * 30), "/");
        }
        header("location: cart.php");
        die();
    }
?>