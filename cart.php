<?php
    require_once("template/boot.php");

    $templateParams["title"] = "Italia's Got Events - Il mio Carrello";
    $templateParams["name"] = "cart_base.php";
    $templateParams["js"] = ["animations.js"];
    $totale = 0;

    if((((isset($_COOKIE["controllo_giornaliero"]) && count($_COOKIE) > 2) || (!isset($_COOKIE["controllo_giornaliero"]) && count($_COOKIE) > 1)) && isset($_GET["bought"]))){
        $tickets = array();
        foreach($_COOKIE as $id_evento => $numero_biglietti){
            if($id_evento !== "PHPSESSID" && $numero_biglietti !== "checked"){
                $free_tickets = $dbh->getNotBoughtTickets($numero_biglietti, $id_evento);
                foreach($free_tickets as $free_ticket){
                    $temp["event_id"] = $id_evento;
                    $temp["ticket_id"] = $free_ticket["id_biglietto"];
                    array_push($tickets, $temp);
                }
                setcookie($id_evento, "", time() - 100, "/");
                $pieces = $dbh->getNotificationPieces($numero_biglietti == 1 ? 1 : 2);
                $evento = $dbh->getEventFromId($id_evento);
                $utente = $dbh->getUserFromId($_SESSION["id_utente"]);
                $messaggio_finale = $pieces[0]["bozza"].$utente["nome"]." ".$utente["cognome"].$pieces[1]["bozza"].($numero_biglietti > 1 ? $numero_biglietti.$pieces[2]["bozza"] : "").$evento["titolo"].".";
                $dbh->sendNotificationToUser($evento["organizzatore"], $messaggio_finale, $pieces[0]["id_notifica"]);
            }
        }
        $dbh->addUserTickets($tickets);
        header("location: ".$_SERVER["PHPSELF"]."?bought=1");
        die();
    }   

    require("template/base.php");
?>