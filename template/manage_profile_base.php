<?php if(isset($_GET["err_1"])): ?>
<h2 class="error"><?php echo "La password attuale non corrisponde.";?></h2>
<?php elseif(isset($_GET["err_2"])): ?>
<h2 class="error"><?php echo "Le password non coincidono.";?></h2>
<?php elseif(isset($_GET["err_3"])): ?>
<h2 class="error"><?php echo "Il nome utente desiderato esiste gi&agrave;.";?></h2>
<?php endif; ?>
<form action="update_user.php" method="POST" enctype="multipart/form-data">
    <label for="hidden_image_loader">Clicca l'immagine per modificare la foto del tuo profilo.</label>
    <input type="file" id="hidden_image_loader" name="image" accept="image/*" class="hidden_image_loader"/><img src=<?php echo UPLOAD_DIR.$user["immagine"]; ?> alt="Immagine del Profilo" class="big_image image_loader"/>
    <?php if($_SESSION["id_utente"] != $dbh->getRootId()):?>
    <label for="username">Nome Utente:</label>
    <input type="text" id="username" name="username" value="<?php echo $user["username"];?>" required/>
    <?php else: ?>
    <br/>
    <h3>Nome Utente:</h3>
    <h3><?php echo $user["username"];?></h3><br/>
    <input type="hidden" name="username" value="root" />
    <?php endif;?>
    <label for="new_password">Nuova Password:</label>
    <input type="password" id="new_password" name="new_password" /><br/>
    <label for="check_password">Ripeti password:</label>
    <input type="password" id="check_password" name="check_password" />
    <label for="city">Citt&agrave;</label>
    <input type="text" id="city" name="city" value="<?php echo ucfirst($user["città"]);?>"/>
    <h2>Preferenze:</h2>
    <?php foreach($dbh->getCategories() as $categoria): ?>
    <label for="category_<?php echo $categoria["id_categoria"]; ?>"><input type="checkbox" id="category_<?php echo $categoria["id_categoria"]; ?>" name="category_<?php echo $categoria["id_categoria"]; ?>" value="<?php echo $categoria["id_categoria"]; ?>" <?php echo in_array($categoria["id_categoria"], explode(",",$user["id_categorie"])) ? "checked":"";?>/><?php echo $categoria["nome_categoria"]; ?></label>
    <?php endforeach; ?>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo $user["email"];?>" required/>
    <label for="phone">Cellulare:</label>
    <input type="tel" id="phone" name="phone" value="<?php echo $user["cellulare"];?>"/>
    <label for="old_password">Password attuale:</label>
    <input type="password" id="old_password" name="old_password" placeholder="*" required/>
    <input type="hidden" name="old_categories" value="<?php echo implode(",", $user["categorie"]);?>"/>
    <input type="submit" value="Salva Modifiche" class="button accept"/>
    <a href="profile.php?id=<?php echo $user["id_utente"];?>" class="button cancel">Annulla</a>
</form>