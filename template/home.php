<div id="home_events">
   <?php foreach($dbh->getRandomEvents("5") as $event): ?>
   <a href="event.php?id=<?php echo $event["id_evento"]; ?>"><img src="<?php echo UPLOAD_DIR.$event["immagine_evento"]; ?>" alt="<?php echo $event["titolo"]; ?>" class="home_image" /></a>
   <?php endforeach; ?>
   <img src="<?php echo UPLOAD_DIR."left_arrow.svg"?>" alt="Vai all'evento precedente" class="icon"/>
   <img src="<?php echo UPLOAD_DIR."left_arrow.svg"?>" alt="Vai all'evento successivo" class="icon" />
</div>
<section>
   <h2>Di Tendenza</h2>
   <?php foreach($dbh->getTrendEvents() as $event): ?>
   <article>
   <?php require("event_list.php"); ?>
   </article>
   <?php endforeach; ?>
</section>
<section>
   <h2>In Arrivo</h2>
   <?php foreach($dbh->getSoonEvents() as $event): ?>
   <article>
   <?php require("event_list.php"); ?>
   </article>
   <?php endforeach; ?>
</section>
<section>
   <h2>Gratuiti</h2>
   <?php foreach($dbh->getFreeEvents() as $event): ?>
   <article>
   <?php require("event_list.php"); ?>
   </article>
   <?php endforeach; ?>
</section>
<section>
   <h2>Nuove Aggiunte</h2>
   <?php foreach($dbh->getNewEvents() as $event): ?>
   <article>
   <?php require("event_list.php"); ?>
   </article>
   <?php endforeach; ?>
</section>