<?php if(isset($_GET["bought"])): ?>
<h2>L'acquisto &egrave; stato completato correttamente.</h2>
<?php endif; ?>
<?php if(isUserLoggedIn() && (isset($_COOKIE["controllo_giornaliero"]) && count($_COOKIE) == 2) || (!isset($_COOKIE["controllo_giornaliero"]) && count($_COOKIE) == 1)): ?>
<h2>Il tuo carrello &egrave; vuoto.<h2>
<?php elseif(!isUserLoggedIn()): ?>
<h2>Devi accedere per poter vedere il tuo carrello.</h2>
<?php else: ?>
<ul id="cart">
<?php foreach($_COOKIE as $id_evento => $numero_biglietti): ?>
<?php if($id_evento !== "PHPSESSID" && $numero_biglietti !== "checked"): $event = $dbh->getEventFromId($id_evento); $totale += $numero_biglietti*$event["prezzo"];?>
    <li>
        <?php require("event_list.php"); ?>
        <h4>Biglietti disponibili: <?php echo $dbh->getTicketsAvailable($id_evento); ?></h4>
        <h4>Quantit&agrave; biglietti: <?php echo $numero_biglietti; ?></h4>
        <h4>Prezzo per biglietto: <?php echo $event["prezzo"]; ?> &euro;</h4>
        <button onclick="removeFromCart(<?php echo $id_evento.','.$numero_biglietti; ?>)" class="button cancel">Rimuovi</button>
    </li>
<?php endif; ?>
<?php endforeach; ?>
</ul>
<h3>Totale: <?php echo $totale;?> &euro;</h3>
<button class="button accept" id="buy_tickets">Procedi con l'acquisto</button>
<?php endif; ?>