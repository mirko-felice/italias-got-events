<section id="full_event">
    <?php if(isset($_GET["err"]) && $_GET["err"] == 1): ?>
    <h2 class="error"><?php echo "L'evento è stato creato, nonostante sia fallito il caricamento dell'immagine.";?></h2>
    <?php elseif(isset($_GET["err"]) && $_GET["err"] == 2): ?>
    <h2 class="error"><?php echo "L'evento è stato modificato, nonostante sia fallito il caricamento dell'immagine.";?></h2>
    <?php endif;?>
    <h2><?php echo $evento["titolo"]; ?></h2>
    <img src="<?php echo UPLOAD_DIR.$evento["immagine_evento"]; ?>" alt="<?php echo $evento["titolo"]; ?>" class="big_image"/>
    <h3>Data: <?php echo date_format(date_create($evento["data_avvenimento"]),"d/m/Y"); echo $evento["data_conclusione"] == null ? "" : (" - ".date_format(date_create($evento["data_conclusione"]),"d/m/Y"));?></h3>
    <h3>Creatore:<br/><?php echo $evento["nome"]." ".$evento["cognome"]; ?></h3>
    <h3><?php echo $evento["nome_categoria"]; ?></h3>
    <h3 class="address"><?php echo ucfirst($evento["luogo_avvenimento"]).", ".$evento["indirizzo"]; ?></h3>
    <h3 id="descr">Descrizione:</h3>
    <p><?php echo $evento["descrizione_lunga"]; ?></p>
    <h4>Biglietti disponibili: <?php echo $dbh->getTicketsAvailable($evento["id_evento"]); ?></h4>
    <?php if(isUserLoggedMe($dbh->getRootId())): ?>
    <?php if($evento["evento_attivo"] == 1): ?>
        <a onclick="confirmDeleteEvent(<?php echo $evento['id_evento'];?>)" class="button cancel">Disabilita evento</a>
        <?php else:?>
        <a onclick="confirmEnableEvent(<?php echo $evento['id_evento'];?>)" class="button accept">Riabilita evento</a>
        <?php endif; ?>
    <?php endif; ?>
    <br/>
    <h4>Prezzo Biglietto: <?php echo $evento["prezzo"] == 0 ? "Gratuito" : $evento["prezzo"]." &euro;"; ?></h4>
    <?php if(isUserLoggedIn() && !isUserLoggedMe($evento["organizzatore"]) && date_diff(date_create($evento["data_avvenimento"]), date_create())->invert == 1): ?>
    <button class="button" onclick="addToCart(<?php echo $evento['id_evento'].','.$dbh->getTicketsAvailable($evento['id_evento']); ?>)">Aggiungi al carrello</button><br/>
    <?php elseif(isUserLoggedMe($evento["organizzatore"])): ?>
    <a onclick="confirmDeleteEvent(<?php echo $evento['id_evento'];?>)" class="button cancel">Cancella</a>
    <a href="manage_event.php?id=<?php echo $evento["id_evento"]; ?>" class="button">Modifica</a><br/>
    <?php endif; ?>
    <h4>Visto <?php echo $evento["numero_visualizzazioni"]; ?> volte</h4>
    <a class="button" href="contacts.php?id=<?php echo $evento["organizzatore"]; ?>">Contatti Organizzatore</a>
</section>
<section>
    <h2 id="other_events">Altri Eventi di <?php echo $evento["nome"]." ".$evento["cognome"]; ?></h2>
    <?php foreach($dbh->getRandomEventsFromManager($evento["organizzatore"], $evento["id_evento"]) as $event): ?>
    <article>
    <?php require("event_list.php"); ?>
    </article>
    <?php endforeach;?> 
</section>
<section>
    <h2>Correlati per Categoria</h2>
    <?php foreach($dbh->getEventsPerCategory($evento["categoria"], $evento["id_evento"]) as $event): ?>
    <article>
    <?php require("event_list.php"); ?>
    </article>
    <?php endforeach;?> 
</section>