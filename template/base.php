<!DOCTYPE html>
<html lang="it">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $templateParams["title"]; ?></title>
    <link rel="stylesheet" type="text/css" href="./css/style.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/functions.js"></script>
    <?php if(isset($templateParams["js"])):
        foreach($templateParams["js"] as $script): ?>
        <script src="js/<?php echo $script; ?>"></script>
    <?php endforeach;
    endif; ?>
</head>
<body>
    <header>
        <div class="header">
            <img src="<?php echo UPLOAD_DIR; ?>menu.svg" alt="Apri Men&ugrave;" class="icon" id="open_menu" />
            <a href="index.php" id="title_header">
                <h1>Italia's Got Events</h1>
            </a>
            <img src="<?php echo isUserLoggedIn() ? UPLOAD_DIR."user_logged.svg" : UPLOAD_DIR."user_not_logged.svg"; ?>" alt="Apri Profilo" class="icon" id="open_profile" />
            <img src="<?php echo UPLOAD_DIR; ?>notifica.svg" alt="Apri Notifiche" class="icon" id="toggle_notification" />
            <img src="<?php echo UPLOAD_DIR; ?>search.svg" alt="Vai alla ricerca" class="icon" id="open_search" />
        </div>
        <nav>
            <div class="header">
                <img src="<?php echo UPLOAD_DIR; ?>close.svg" alt="Chiudi Men&ugrave;" class="icon" id="close_menu" />
            </div>
            <ul>
                <li><a href="index.php"><img src="<?php echo UPLOAD_DIR; ?>home.svg" alt="Vai alla Home" class="icon" />Home</a></li>
                <li id="toggle_events" ><img src="<?php echo UPLOAD_DIR; ?>event.svg" alt="Apri Lista Eventi" class="icon" />Eventi
                    <img src="<?php echo UPLOAD_DIR; ?>freccia.svg" alt="Apri Lista Eventi" id="arrow_events" />
                    <ul id="events" >
                        <li><a href="search.php?action=0"><img src="<?php echo UPLOAD_DIR; ?>graph.svg" alt="Vai agli Eventi di tendenza" class="icon" />Di Tendenza</a></li>
                        <li><a href="search.php?action=1"><img src="<?php echo UPLOAD_DIR; ?>soon.svg" alt="Vai agli Eventi in arrivo" class="icon" />In Arrivo</a></li>
                        <li><a href="search.php?action=2"><img src="<?php echo UPLOAD_DIR; ?>gratis.svg" alt="Vai agli Eventi Gratuiti" class="icon" />Gratuiti</a></li>
                        <li><a href="search.php?action=3"><img src="<?php echo UPLOAD_DIR; ?>new.svg" alt="Vai agli Eventi Aggiunti da poco " class="icon" />Nuove Aggiunte</a></li>
                        <?php if(isUserLoggedIn()): ?>
                        <li><a href="search.php?action=4"><img src="<?php echo UPLOAD_DIR; ?>suggestion.svg" alt="Vai agli Eventi consigliati" class="icon" />Consigliati</a></li>
                        <li><a href="search.php?action=5"><img src="<?php echo UPLOAD_DIR; ?>zona.svg" alt="Vai agli Eventi nella mia zona" class="icon" />Nella Mia Zona</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
                <li id="toggle_categories"><img src="<?php echo UPLOAD_DIR; ?>categories.svg" alt="Apri Lista Categorie" class="icon" />Categorie
                    <img src="<?php echo UPLOAD_DIR; ?>freccia.svg" alt="Apri Lista Categorie" id="arrow_categories" />
                    <ul id="menu_categories">
                    <?php foreach($dbh->getCategories() as $categoria): ?>
                        <li><a href="search.php?category_<?php echo $categoria["id_categoria"]; ?>=on"><?php echo $categoria["nome_categoria"]; ?></a></li>
                    <?php endforeach; ?>
                    </ul>
                </li>
                <li><a href="managers.php"><img src="<?php echo UPLOAD_DIR; ?>organizator.svg" alt="Vai agli Organizzatori" class="icon" />Organizzatori</a></li>
                <li><a href="contacts.php?id=<?php echo $dbh->getRootId(); ?>"><img src="<?php echo UPLOAD_DIR; ?>contacts.svg" alt="Vai ai Contatti" class="icon" />Contatti</a></li>
            </ul>
        </nav>
        <div id="profile"> 
            <div class="header">
                <?php if(isUserLoggedIn()): $user_base = $dbh->getUserFromId($_SESSION["id_utente"]);?>
                <h2><?php echo $user_base["nome"]." ".$user_base["cognome"];?></h2>
                <?php endif; ?>
                <img src="<?php echo UPLOAD_DIR; ?>close.svg" alt="Chiudi Profilo" class="icon" id="close_profile" />
            </div>
            <?php if(isUserLoggedIn()): ?>
                <ul>
                    <li><a href="profile.php?id=<?php echo $_SESSION["id_utente"]; ?>"><img src="<?php echo UPLOAD_DIR; ?>user_logged.svg" alt="Vai al mio Profilo" class="icon" />Vai al mio profilo</a></li>
                    <li><a href="cart.php"><img src="<?php echo UPLOAD_DIR; ?>cart.svg" alt="Vai al mio Carrello" class="icon" />Vai al carrello</a></li>
                    <li><a href="logout.php"><img src="<?php echo UPLOAD_DIR; ?>logout.svg" alt="Disconnettiti" class="icon" />Disconnettiti</a></li>
                </ul>
            <?php else: ?>
                <form action="login.php" method="POST" id="login_form">
                    <label for="username_login">Nome Utente:</label>
                    <input type="text" id="username_login" name="username_login" required/>
                    <label for="password_login">Password:</label>
                    <input type="password" id="password_login" name="password_login" required />
                    <input type="submit" value="Entra" class="button"/>
                </form>
                <br/>
                <a href="register.php" class="button">Registrati</a>
            <?php endif; ?>
        </div>
        <div id="search_bar" >
            <div class="header">
                <img src="<?php echo UPLOAD_DIR; ?>left_arrow.svg" alt="Chiudi barra di ricerca" class="icon" id="close_search" />
                <form action="search.php" method="get">
                    <label for="search_content" class="accessibility">Contenuto da cercare</label>
                    <input type="search" list="events_suggest" name="title" placeholder="Cerca..." id="search_content"/>
                    <label for="search" class="accessibility">Cerca</label>
                    <input type="image" src="<?php echo UPLOAD_DIR; ?>search.svg" alt="Cerca" class="icon" id="search" />
                </form>
            </div>
            <datalist id="events_suggest">
                <?php foreach($dbh->getAllEvents() as $event_suggest) :?>
                <option value="<?php echo $event_suggest["titolo"];?>">
                <?php endforeach; ?>
            </datalist>
        </div>
        <div id="notifications">
            <?php if(!isUserLoggedIn()): ?>
            <h2>Notifiche</h2>
            <a href=# id="go_to_login">Se vuoi vedere le tue notifiche, clicca qui per accedere.</a>
            <?php endif; ?>
        </div>
    </header>
    <main>
    <?php if(isset($_GET["login_err"])): ?>
    <h2 class="error">Nome utente e/o password errati.</h2>
    <?php endif; ?>
    <?php if(isset($templateParams["name"])): 
        require($templateParams["name"]); 
        endif; ?>
    </main>
    <footer>
        <a href="contacts.php?id=<?php echo $dbh->getRootId(); ?>">Contatti</a>
        <a href="cookies.php">Informativa sui cookies</a>
        <p>Made by M&M</p>
    </footer>
</body>
</html>