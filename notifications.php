<?php
require_once("template/boot.php");
if(isUserLoggedIn()){
    $notifiche = $dbh->getUserNotifications();
    header('Content-Type: application/json');
    echo json_encode($notifiche);
}
?>