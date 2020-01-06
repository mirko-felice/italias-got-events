<?php
    require_once("template/boot.php");
    if(!isUserLoggedIn()){
        header("location: 404.php");
        die();
    } else {
        $dbh->deleteEvent($_GET["id"]);
        foreach($dbh->getUserWithEventTickets($_GET["id"]) as $user){
            $pieces = $dbh->getNotificationPieces(7);
            $utente = $dbh->getUserFromId($user["proprietario"]);
            $manager = $dbh->getUserFromId($user["organizzatore"]);
            $messaggio_finale = $pieces[0]["bozza"].$utente["nome"]." ".$utente["cognome"].$pieces[1]["bozza"].$manager["nome"]." ".$manager["cognome"].$pieces[2]["bozza"].$dbh->getEventFromId($_GET["id"])["titolo"].$pieces[3]["bozza"];
            $dbh->sendNotificationToUser($user["proprietario"], $messaggio_finale, $pieces[0]["id_notifica"]);
        }
        if($_SESSION["id_utente"] == $dbh->getRootId()){
            $pieces = $dbh->getNotificationPieces(8);
            $evento = $dbh->getEventFromId($_GET["id"]);
            $messaggio_finale = $pieces[0]["bozza"].$evento["titolo"].$pieces[1]["bozza"];
            $dbh->sendNotificationToUser($evento["organizzatore"], $messaggio_finale, $pieces[0]["id_notifica"]);
            header("location: event.php?id=".$_GET["id"]);
            die();
        } else {
            header("location: profile.php?id=".$_SESSION["id_utente"]);
            die();
        }
    }
?>