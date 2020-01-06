<form action="search.php" method="GET">
    <div id="complete_search_bar">
        <img src="<?php echo UPLOAD_DIR; ?>filter.svg" alt="Apri Filtri" class="icon" id="toggle_filters" />
        <label for="complete_search_content" class="accessibility">Contenuto da cercare</label>
        <input type="search" list="events_suggest" name="title" placeholder="<?php echo $title; ?>" value="<?php echo $title; ?>" id="complete_search_content"/>
        <label for="complete_search" class="accessibility">Cerca</label>
        <input type="image" src="<?php echo UPLOAD_DIR; ?>search.svg" alt="Cerca" class="icon" id="complete_search" />
    </div>
    <div id="filter">
        <label for="cities">Citt&agrave;</label>
        <select name="cities" id="cities" >
            <option value="" <?php echo $city === "" ? "selected" : ""; ?>>Qualsiasi</option>
            <?php foreach($dbh->getCities() as $city_option): ?>
                <option value="<?php echo $city_option["luogo_avvenimento"]; ?>" <?php echo $city_option["luogo_avvenimento"] === $city ? "selected" : ""; ?>><?php echo ucfirst($city_option["luogo_avvenimento"]); ?></option>
            <?php endforeach; ?>
        </select>
        <h3>Prezzo</h3>
        <div>
        <label for="min_price">Tra <input type="number" name="min_price" id="min_price" min="0" max="<?php echo $dbh->getMaxPrice(); ?>" value="<?php echo $min_price; ?>"/></label>
        <label for="max_price"> e <input type="number" name="max_price" id="max_price" min="0" max="<?php echo $dbh->getMaxPrice(); ?>" value="<?php echo $max_price; ?>"/></label>
        </div>
        <h3>Disponibilit&agrave;</h3>
        <label for="tickets">
        <select name="tickets" id="tickets">
            <option value="-1" <?php echo $availability == -1 ? "selected" : ""; ?>>Qualsiasi</option>
            <option value="5" <?php echo $availability == 5 ? "selected" : ""; ?>>Pi&ugrave; di 5</option>
            <option value="10" <?php echo $availability == 10 ? "selected" : ""; ?>>Pi&ugrave; di 10</option>
            <option value="20" <?php echo $availability == 20 ? "selected" : ""; ?>>Pi&ugrave; di 20</option>
            <option value="50" <?php echo $availability == 50 ? "selected" : ""; ?>>Pi&ugrave; di 50</option>
        </select>
        biglietti disponibili</label>
        <label for="check_not_available"><input type="checkbox" name="check_not_available" id="check_not_available" <?php echo $availability >= 5 ? "disabled" : ""; ?><?php echo isset($_GET["check_not_available"]) ? "checked" : ""; ?>/>Non mostrare gli eventi con biglietti esauriti</label>
        <h3>Data</h3>
        <label for="start_date">Data di inizio periodo
        <input type="date" name="start_date" id="start_date" value="<?php echo $start_date; ?>" min="<?php echo date("Y-m-d"); ?>"/></label>
        <label for="end_date" <?php echo !isset($_GET["check_more_days"]) ? "style = 'display : none;'" : ""; ?>>Data di fine periodo
        <input type="date" name="end_date" id="end_date" value="<?php echo $end_date; ?>"/></label>
        <label for="check_more_days"><input type="checkbox" name="check_more_days" id="check_more_days" <?php echo isset($_GET["check_more_days"]) ? "checked" : ""; ?>/>Ricerca per Periodo</label>
        <h3>Categorie</h3>
        <?php $i=0; foreach($dbh->getCategories() as $categoria): $i++;?>
        <label for="category_<?php echo $categoria["id_categoria"]; ?>" class="category_filter <?php echo $i > 3 ? "hide_default_cat" : ""; ?>"><input type="checkbox" name="category_<?php echo $categoria["id_categoria"]; ?>" id="category_<?php echo $categoria["id_categoria"]; ?>" <?php echo in_array($categoria["id_categoria"], $categories) ? "checked" : ""; ?> /><?php echo $categoria["nome_categoria"]; ?></label>
        <?php endforeach; ?>
        <img src="<?php echo UPLOAD_DIR; ?>show_more.svg" id="show_more_categories" alt="Mostra altre categorie"/>
        <fieldset>
        <legend>Organizzatore</legend>
        <?php $i=0; foreach($dbh->getManagers() as $manager_check): $i++;?>
        <label for="manager_<?php echo $manager_check["id_utente"]; ?>" class="manager_filter <?php echo $i > 3 ? "hide_default_man" : ""; ?>" ><input type="radio" name="manager" value="<?php echo $manager_check["id_utente"]; ?>" id="manager_<?php echo $manager_check["id_utente"]; ?>" <?php echo $manager_check["id_utente"] == $manager ? "checked" : ""; ?>/><?php echo $manager_check["nome"]." ".$manager_check["cognome"]; ?></label>
        <?php endforeach; ?>
        </fieldset>
        <img src="<?php echo UPLOAD_DIR; ?>show_more.svg" id="show_more_managers" alt="Mostra altri organizzatori"/><br/>
        <input type="submit" value="Applica Filtri" class="button accept"/><br/>
        <label for="order">Ordina per:
        <select name="order" id="order">
            <option class="order-value" value="dc" <?php echo $order === "dc" ? "selected" : ""; ?>>data crescente</option>
            <option class="order-value" value="dd" <?php echo $order === "dd" ? "selected" : ""; ?>>data decrescente</option>
            <option class="order-value" value="pc" <?php echo $order === "pc" ? "selected" : ""; ?>>prezzo crescente</option>
            <option class="order-value" value="pd" <?php echo $order === "pd" ? "selected" : ""; ?>>prezzo decrescente</option>
        </select>
        </label>
    </div>
</form>
<section>
<h2>Risultati</h2>
<?php foreach($events as $event): ?>
    <article>
    <?php require("search_event_list.php"); ?>
    </article>
<?php endforeach; ?>
</section>