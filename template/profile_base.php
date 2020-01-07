<div>
    <?php if(isset($_GET["err"]) && $_GET["err"] == 1): ?>
    <h2 class="error"><?php echo "La registrazione &egrave; avvenuta con successo, nonostante sia fallito il caricamento dell'immagine.";?></h2>
    <?php elseif(isset($_GET["err"]) && $_GET["err"] == 2): ?>
    <h2 class="error"><?php echo "La modifica del profilo &egrave; avvenuta con successo, nonostante sia fallito il caricamento dell'immagine.";?></h2>
    <?php elseif(isset($_GET["refund"])): ?>
    <h2 class="error"><?php echo "Il rimborso &egrave; avvenuto con successo";?></h2>
    <?php endif;?>
    <img src="<?php echo UPLOAD_DIR.$user["immagine"]; ?>" alt="Immagine di <?php echo $user["nome"]." ".$user["cognome"]; ?>" class="big_image"/>
    <h2><?php echo $user["nome"]." ".$user["cognome"]; ?></h2>
    <?php if(isUserLoggedMe($user["id_utente"])): ?>
    <a href="manage_profile.php?id=<?php echo $user["id_utente"]; ?>" class="button">Modifica Profilo</a>
    <?php elseif(isUserLoggedMe($dbh->getRootId())): ?>
        <?php if($user["utente_attivo"] == 1): ?>
        <a onclick="confirmDeleteUser(<?php echo $user['id_utente'];?>)" class="button cancel">Disabilita utente</a>
        <?php else:?>
        <a onclick="confirmEnableUser(<?php echo $user['id_utente'];?>)" class="button accept">Riabilita utente</a>
        <?php endif; ?>
    <?php endif; ?>
    <div id="pref_div">
    <h3 id="pref">Preferenze</h3>
    <ul id="categories_profile">
    <?php if(isset($user["nomi_categorie"])): ?>
        <?php foreach(explode(",", $user["nomi_categorie"]) as $categoria): ?>
        <li><?php echo $categoria; ?></li>
        <?php endforeach; ?>
    <?php endif; ?>
    </ul>
    </div>
    <div id="cont_div">
    <h3 id="cont">Contatti</h3>
    <ul>
        <li><a href="mailto:<?php echo $user["email"]; ?>"><?php echo $user["email"]; ?></a></li>
        <?php if(strcmp($user["cellulare"], "null")): ?>
        <li><?php echo $user["cellulare"]; ?></li>
        <?php endif; ?>
    </ul>
    </div>
</div>
<div id="tabs_bar">
    <button class="tab_links button selected" id="toggle_events_organized" onclick="openTab(event,'events_organized')" >Organizzati da <?php echo isUserLoggedMe($user["id_utente"]) ? "me" : "lui"; ?></button>
    <button class="tab_links button" id="toggle_events_future" onclick="openTab(event,'events_future')">A cui partecip<?php echo isUserLoggedMe($user["id_utente"]) ? "o" : "a"; ?></button>
    <button class="tab_links button" id="toggle_events_past" onclick="openTab(event,'events_past')">A cui <?php echo isUserLoggedMe($user["id_utente"]) ? "ho" : "ha"; ?> partecipato</button>
</div>
<div class="tab_content" id="events_organized">
    <?php if(isUserLoggedMe($user["id_utente"])): ?>
    <a href="manage_event.php" class="button">Nuovo Evento</a>
    <?php endif; ?>
    <?php foreach($dbh->getEventsManagedFromUserId($user["id_utente"]) as $event): ?>
    <article>
    <a href="event.php?id=<?php echo $event["id_evento"]; ?>"><h2><?php echo $event["titolo"]; ?></h2></a>
    <img src="<?php echo UPLOAD_DIR.$event["immagine_evento"]; ?>" alt="<?php echo $event["titolo"]; ?>" class="big_image" />
    <h3>Data: <?php echo date_format(date_create($event["data_avvenimento"]),"d/m/Y"); ?></h3>
    <h3><?php echo $event["nome_categoria"]; ?></h3>
    <h3>Prezzo biglietto: <?php echo $event["prezzo"] == 0 ? "Gratuito" : $event["prezzo"]." &euro;"; ?></h3>
    <h3>Biglietti venduti: <?php echo $dbh->getSoldTickets($event["id_evento"]); ?></h3>
    <h3 class="address"><?php echo ucfirst($event["luogo_avvenimento"]).", ".$event["indirizzo"]; ?></h3>
    <?php if(isUserLoggedMe($user["id_utente"]) && (time() - strtotime($event["data_avvenimento"])) < 0): ?>
    <a href="manage_event.php?id=<?php echo $event["id_evento"];?>" class="button accept">Modifica</a>
    <a onclick="confirmDeleteEvent(<?php echo $event['id_evento'];?>)" class="button cancel">Cancella</a>
    <?php endif; ?>
    </article>
    <?php endforeach; ?>
</div>
<div class="tab_content" id="events_future">
    <?php foreach($dbh->getEventsFutureFromUserId($user["id_utente"]) as $event): ?>
    <article>
    <?php require("event_list.php"); ?>
    <h4>Prezzo Biglietto: <?php echo $event["prezzo"] == 0 ? "Gratuito" : $event["prezzo"]." &euro;"; ?></h4>
    <h4>Biglietti acquistati: <?php echo count($dbh->getUserTicketsOfEvent($user["id_utente"],$event["id_evento"])); ?></h4>
    <?php if(isUserLoggedMe($user["id_utente"])): ?>
    <button class="button cancel" onclick="refundTickets(<?php echo $event['id_evento'].', '.count($dbh->getUserTicketsOfEvent($user['id_utente'], $event['id_evento'])); ?>)">Rimborsa</button>
    <?php endif; ?>
    </article>
    <?php endforeach; ?>
</div>
<div class="tab_content" id="events_past">
    <?php foreach($dbh->getEventsPastFromUserId($user["id_utente"]) as $event): ?>
    <article>
    <?php require("event_list.php"); ?>
    <h4>Biglietti acquistati: <?php echo count($dbh->getUserTicketsOfEvent($user["id_utente"], $event["id_evento"])); ?></h4>
    <h4>Prezzo Biglietto: <?php echo $event["prezzo"] == 0 ? "Gratuito" : $event["prezzo"]." &euro;"; ?></h4>
    </article>
    <?php endforeach; ?>
</div>