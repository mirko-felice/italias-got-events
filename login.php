<?php 
    require_once("template/boot.php");
    $result = $dbh->checkLogin($_POST["username_login"], $_POST["password_login"]);
    if($result){
        $_SESSION["id_utente"] = $result;
    } else {
        if(strstr($_SERVER["HTTP_REFERER"], "login_err=1")){
            header("location: ".$_SERVER["HTTP_REFERER"]);
            die();
        } else {
            if(strchr($_SERVER["HTTP_REFERER"], "?")){
                header("location: ".$_SERVER["HTTP_REFERER"]."&login_err=1");
                die();
            } else {
                header("location: ".$_SERVER["HTTP_REFERER"]."?login_err=1");
                die();
            }
        }
    }

    if(!isset($_COOKIE["controllo_giornaliero"])){
        foreach($dbh->getEventsFutureFromUserId($_SESSION["id_utente"]) as $event){
            if(date_diff(date_create($event["data_avvenimento"]), date_create())->days <= 1){
                $pieces = $dbh->getNotificationPieces(5);
                $messaggio_finale = $pieces[0]["bozza"].$event["titolo"].$pieces[1]["bozza"].$event["orario"];
                $dbh->sendNotificationToUser($_SESSION["id_utente"], $messaggio_finale, $pieces[0]["id_notifica"]);    
            }
        }
        $user = $dbh->getUserFromId($_SESSION["id_utente"]);
        $date = date_create($user["data_di_nascita"]);
        if(date_format($date, "m") == date("m") && date_format($date, "d") == date("d")){
            $pieces = $dbh->getNotificationPieces(6);
            $messaggio_finale = $pieces[0]["bozza"].$user["nome"]." ".$user["cognome"].$pieces[1]["bozza"].(date("Y") - date_format($date, "Y")).$pieces[2]["bozza"];
            $dbh->sendNotificationToUser($_SESSION["id_utente"], $messaggio_finale, $pieces[0]["id_notifica"]);   
        }
        setcookie("controllo_giornaliero", "checked", strtotime("tomorrow 00:00"), "/");
    }

    header("location: ".str_replace(["?login_err=1", "&login_err=1"], "", $_SERVER["HTTP_REFERER"]));
    die();
?>