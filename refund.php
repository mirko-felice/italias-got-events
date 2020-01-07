<?php
    require_once("template/boot.php");
    if(!isUserLoggedIn()){
        header("location: 404.php");
        die();
    } else {
        $tickets = $dbh->getUserTicketsOfEvent($_SESSION["id_utente"], $_GET["id"]);
        $manager = $dbh->getEventFromId($_GET["id"])["organizzatore"];
        $dbh->refundTickets($tickets, $manager, $_GET["number"]);
        $pieces = $dbh->getNotificationPieces($_GET["number"] == 1 ? 3 : 4);
        $user = $dbh->getUserFromId($_SESSION["id_utente"]);
        $messaggio_finale = $pieces[0]["bozza"].$user["nome"]." ".$user["cognome"].$pieces[1]["bozza"].($_GET["number"] > 1 ? $_GET["number"].$pieces[2]["bozza"] : "").$dbh->getEventFromId($tickets[0]["evento"])["titolo"];
        $dbh->sendNotificationToUser($manager, $messaggio_finale, $pieces[0]["id_notifica"]);
        header("location: ".$_SERVER["HTTP_REFERER"].(strstr($_SERVER["HTTP_REFERER"], "&refund=1") ? "" : "&refund=1"));
        die();
    }
?>