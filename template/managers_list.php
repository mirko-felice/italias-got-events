<?php foreach($templateParams["organizators"] as $organizator) : ?>
    <article>
        <a class="manager_h" href="profile.php?id=<?php echo $organizator["id_utente"];?>"><img src="<?php echo UPLOAD_DIR.$organizator["immagine"]; ?>" alt="Profilo di <?php echo $organizator["nome"]." ".$organizator["cognome"] ?>" class="big_image" /></a>
        <a class="manager_h" href="profile.php?id=<?php echo $organizator["id_utente"];?>"><h2 class="manager_h"><?php echo $organizator["nome"]." ".$organizator["cognome"] ?></h2></a>
        <p>Totale eventi organizzati: <?php echo $dbh->getUserEventsCount($organizator["id_utente"]); ?></p>
        <p>Categorie trattate: <?php echo implode(", ", array_column($dbh->getUserEventsCategories($organizator["id_utente"]), "nome_categoria")); ?></p>
        <a href="search.php?manager=<?php echo $organizator["id_utente"]; ?>" class="button">Vai ai suoi eventi</a>
        <a href="contacts.php?id=<?php echo $organizator["id_utente"]; ?>" class="button">Contatti</a>
    </article>
<?php endforeach; ?>