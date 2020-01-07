<form action="<?php echo !isset($event) ? "create_event.php" : "update_event.php"; ?>" method="POST"  enctype="multipart/form-data">
    <label for="hidden_image_loader">Clicca l'immagine per caricare la foto per il tuo evento.</label>
    <input type="file" name="image" accept="image/*" id="hidden_image_loader" class="hidden_image_loader"/>
    <img src=<?php echo isset($event) ? UPLOAD_DIR.$event["immagine_evento"] : UPLOAD_DIR."event_default.jpg"; ?> alt="Modifica Immagine dell'evento" class="big_image image_loader"/>
    <label for="title">Titolo Evento</label>
    <input type="text" id="title" name="title" required value="<?php echo isset($event) ? $event["titolo"]: "";?>"/>
    <label for="city">Citt&agrave; Evento</label>
    <input type="text" id="city" name="city" required value="<?php echo isset($event) ? ucfirst($event["luogo_avvenimento"]): "";?>" />
    <label for="address">Indirizzo Evento</label>
    <input type="text" id="address" name="address" required value="<?php echo isset($event) ? $event["indirizzo"]: "";?>"/>
    <label for="start_date">Data d'inizio Evento<br/>
    <input type="date" id="start_date" name="start_date" min="<?php echo date("Y-m-d"); ?>" required value="<?php echo isset($event) ? $event["data_avvenimento"]: "";?>"/></label>
    <label for="end_date" <?php echo !isset($event) || (isset($event) && $event["data_conclusione"] === "0000-00-00") ? "style = 'display : none;'" : ""; ?>>Data di fine Evento<br/>
    <input type="date" id="end_date" name="end_date" min="<?php echo date("Y-m-d"); ?>" value="<?php echo isset($event) ? $event["data_conclusione"]: "";?>"/></label>
    <label for="check_more_days" class="check_label"><input type="checkbox" name="check_more_days" value="Evento in più giorni" id="check_more_days" <?php echo isset($event["data_conclusione"]) ? "checked" : ""; ?>/>Evento in pi&ugrave; giorni</label>
    <label class="hour">Orario di inizio: <input type="time" name="time" required value="<?php echo isset($event) ? substr($event["orario"], 0, 5): "";?>"/></label>
    <label for="categories">Categoria<br/>
    <select name="category" id="categories">
    <?php foreach($dbh->getCategories() as $categoria): ?>
        <option value="<?php echo $categoria["id_categoria"]; ?>" <?php echo isset($event) && $event["categoria"] === $categoria["id_categoria"] ? "selected" : "";?>><?php echo $categoria["nome_categoria"]; ?></option>
    <?php endforeach; ?>
    </select>
    </label>
    <label for="long_desc">Descrizione Completa</label>
    <textarea id="long_desc" name="long_desc" required><?php echo isset($event) ? $event["descrizione_lunga"] : "";?></textarea>
    <?php if(!isset($event)): ?>
    <label class="price_label">Prezzo(in euro): <input type="number" name="price" min="0" required /></label>
    <label class="price_label">Numero Biglietti: <input type="number" name="number_tickets" min="0" required /></label>
    <?php endif; ?>
    <input type="hidden" name="id_evento" value="<?php echo isset($event) ? $event["id_evento"] : ""; ?>" />
    <input type="submit" class="button accept" value="<?php echo !isset($event) ? "Crea" : "Salva Modifiche"; ?>" />
    <a class="button cancel" onclick="goBack()">Annulla</a>
</form>