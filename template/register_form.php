<form action="register_user.php" method="post" enctype="multipart/form-data" >
    <label for="hidden_image_loader">Clicca l'immagine per caricare una foto per il tuo profilo.</label>
    <input type="file" id="hidden_image_loader" name="image" accept="image/*" class="hidden_image_loader"/>
    <img src=<?php echo UPLOAD_DIR."user_not_logged.svg"; ?> alt="Scegli immagine del profilo" id="image" class="big_image image_loader"/>
    <label for="username">Nome Utente:</label>
    <input type="text" id="username" name="username" placeholder="*" required />
    <label for="name" >Nome:</label>
    <input type="text" id="name" name="name" placeholder="*" autofocus required />
    <label for="surname" >Cognome:</label>
    <input type="text" id="surname" name="surname" placeholder="*" required />
    <label for="email" >Email:</label>
    <input type="email" id="email" name="email" placeholder="*" required />
    <label for="dateofbirth" >Data di nascita:</label>
    <input type="date" id="dateofbirth" name="dateofbirth" max="<?php echo date("Y-m-d");?>" required />
    <label for="city" >Citt&agrave; di residenza:</label>
    <input type="text" id="city" name="city" placeholder="*" required />
    <label for="phone" >Cellulare:</label>
    <input type="tel" id="phone" name="phone" pattern="^[0-9]+$" maxlength="15" />
    <h3>Categorie di preferenza:</h3>
    <?php foreach($dbh->getCategories() as $categoria): ?>
    <label for="category_<?php echo $categoria["id_categoria"]; ?>"><input type="checkbox" id="category_<?php echo $categoria["id_categoria"]; ?>" name="category_<?php echo $categoria["id_categoria"]; ?>" /><?php echo $categoria["nome_categoria"]; ?></label>
    <?php endforeach; ?>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="*" minlength="6" required />
    <label for="check_password">Ripeti la password:</label>
    <input type="password" id="check_password" name="check_password" placeholder="*" required /><br/>
    <input type="submit" value="Registrati" class="button accept"/>
    <p>I campi contrassegnati con * sono obbligatori.</p>
    <a onclick="goBack()" class="button cancel">Annulla</a>
</form>