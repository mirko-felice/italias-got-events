    <a href="event.php?id=<?php echo $event["id_evento"]; ?>"><h2><?php echo $event["titolo"]; ?></h2></a>
    <img src="<?php echo UPLOAD_DIR.$event["immagine_evento"]; ?>" alt="<?php echo $event["titolo"]; ?>" class="big_image" />
    <h3>Data: <?php echo date_format(date_create($event["data_avvenimento"]),"d/m/Y"); ?></h3>
    <h3>Creatore:<br/><?php echo $event["nome"]." ".$event["cognome"]; ?></h3>
    <h3><?php echo $event["nome_categoria"]; ?></h3>
    <h3 class="address"><?php echo ucfirst($event["luogo_avvenimento"]).", ".$event["indirizzo"]; ?></h3>
    <h4>Biglietti disponibili: <?php echo $dbh->getTicketsAvailable($event["id_evento"]); ?></h4>
    <h4>Prezzo Biglietto: <?php echo $event["prezzo"] == 0 ? "Gratuito" : $event["prezzo"]." &euro;"; ?></h4>
    <h4>Visto <?php echo $event["numero_visualizzazioni"]; ?> volte</h4>