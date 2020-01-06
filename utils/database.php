<?php 
    class DatabaseHelper{
        private $db;

        public function __construct($servername, $username, $password, $dbname){
            $this->db = new mysqli($servername, $username, $password, $dbname);
            if ($this->db->connect_error) {
                die("Connection failed: " . $this->db->connect_error);
            }
        }

        public function checkLogin($username, $password){
            $stmt = $this->db->prepare("SELECT id_utente, password FROM utenti WHERE username = ? AND utente_attivo = TRUE");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            return (count($result) == 1 && password_verify($password, $result[0]["password"])) ? $result[0]["id_utente"] : false;
        }

        public function registerUser($username, $password, $email, $name, $surname, $dateofbirth, $phone, $city, $img){
            $stmt = $this->db->prepare("INSERT INTO `utenti`(`Id_utente`, `Nome`, `Cognome`, `Data_di_nascita`, `Email`, `Password`, `Città`, `Cellulare`, `Immagine`, `utente_Attivo`, `Username`) 
                                  VALUES (null, ?, ?, ?, ?, ?, ?, ?, ?, TRUE, ?)");
            $password = password_hash($password, PASSWORD_BCRYPT);
            if(strlen($img) == 0){
                $img = "user_not_logged.svg";
            }
            $city = strtolower($city);
            $stmt->bind_param("sssssssss", $name, $surname, $dateofbirth, $email, $password, $city, $phone, $img, $username);
            $stmt->execute();
            return $stmt->insert_id;
        }

        public function getCategories(){
            $stmt = $this->db->prepare("SELECT * FROM categorie");
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }

        public function getManagers(){ 
            $query = "SELECT DISTINCT id_utente, nome, cognome, immagine FROM utenti, eventi WHERE id_utente = organizzatore";
            if(!isUserLoggedMe($this->getRootId())){
                $query = $query." AND evento_attivo = TRUE";
            }
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }
        
        public function getCities(){
            $stmt = $this->db->prepare("SELECT DISTINCT luogo_avvenimento FROM eventi");
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }

        public function getMaxPrice(){
            $stmt = $this->db->prepare("SELECT MAX(prezzo) as max_prezzo FROM eventi");
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0]["max_prezzo"];
        }

        public function getUserFromId($id_utente){
            $stmt = $this->db->prepare("SELECT *, (SELECT GROUP_CONCAT(categoria) FROM utente_ha_categoria WHERE utente = id_utente GROUP BY utente) as id_categorie, (SELECT GROUP_CONCAT(nome_categoria) FROM utente_ha_categoria, categorie WHERE utente = id_utente AND categoria = id_categoria GROUP BY utente) as nomi_categorie FROM utenti WHERE id_utente = ?");
            $stmt->bind_param("i", $id_utente);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0];
        }

        public function getRootId(){
            $stmt = $this->db->prepare("SELECT id_utente FROM utenti WHERE username = 'root'");
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0]["id_utente"];
        }

        public function getEventsManagedFromUserId($id_utente){
            $stmt = $this->db->prepare("SELECT * FROM eventi, categorie WHERE organizzatore = ? AND categoria = id_categoria AND evento_attivo = TRUE");
            $stmt->bind_param("i", $id_utente);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }

        public function getEventsFutureFromUserId($id_utente){
            $stmt = $this->db->prepare("SELECT * FROM eventi, biglietti, categorie, utenti WHERE organizzatore != ? AND proprietario = ? AND evento_attivo = TRUE AND (data_avvenimento > CURDATE() OR (data_avvenimento = CURDATE() AND orario > CURTIME())) AND evento = id_evento AND categoria = id_categoria AND id_utente = organizzatore GROUP BY id_evento");
            $stmt->bind_param("ii", $id_utente, $id_utente);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }

        public function getEventsPastFromUserId($id_utente){
            $stmt = $this->db->prepare("SELECT * FROM eventi, biglietti, categorie, utenti WHERE organizzatore != ? AND proprietario = ? AND evento_attivo = TRUE AND (data_avvenimento < CURDATE() OR (data_avvenimento = CURDATE() AND orario < CURTIME())) AND evento = id_evento AND id_utente = organizzatore AND categoria = id_categoria GROUP BY id_evento");
           $stmt->bind_param("ii", $id_utente, $id_utente);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }

        public function getEventFromId($id_evento){
            $stmt = $this->db->prepare("SELECT * FROM eventi, categorie, utenti WHERE id_evento = ? AND id_categoria = categoria AND id_utente = organizzatore");
            $stmt->bind_param("i", $id_evento);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0];
        }

        public function getTicketsAvailable($id_evento){
            $stmt = $this->db->prepare("SELECT COUNT(*) as numero_biglietti FROM eventi, biglietti WHERE evento = ? AND evento = id_evento AND proprietario = organizzatore");
            $stmt->bind_param("i", $id_evento);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0]["numero_biglietti"];
        }

        public function getRandomEventsFromManager($id_organizzatore, $id_evento){
            $stmt = $this->db->prepare("SELECT * FROM eventi, categorie, utenti WHERE organizzatore = ? AND id_evento != ? AND id_utente = organizzatore AND categoria = id_categoria AND evento_attivo = TRUE ORDER BY RAND() LIMIT 3 ");
            $stmt->bind_param("ii", $id_organizzatore, $id_evento);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }

        public function getEventsPerCategory($id_categoria, $id_evento){
            $stmt = $this->db->prepare("SELECT * FROM eventi, categorie, utenti WHERE categoria = ? AND id_utente = organizzatore AND id_evento != ? AND categoria = id_categoria AND evento_attivo = TRUE ORDER BY RAND() LIMIT 3");
            $stmt->bind_param("ii", $id_categoria, $id_evento);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }

        public function getUserEventsCount($id_utente){
            $stmt = $this->db->prepare("SELECT COUNT(*) as totale_eventi FROM eventi WHERE organizzatore = ? AND evento_attivo = TRUE");
            $stmt->bind_param("i", $id_utente);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0]["totale_eventi"];
        }

        public function getUserEventsCategories($id_utente){
            $stmt = $this->db->prepare("SELECT DISTINCT nome_categoria FROM categorie, eventi WHERE organizzatore = ? AND categoria = id_categoria AND evento_attivo = TRUE GROUP BY categoria, nome_categoria");
            $stmt->bind_param("i", $id_utente);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }
        
        public function getFreeEvents(){
            $stmt = $this->db->prepare("SELECT * FROM eventi, categorie, utenti WHERE prezzo = 0 AND categoria = id_categoria AND id_utente = organizzatore AND evento_attivo = TRUE ORDER BY RAND() LIMIT 3");
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }

        public function getSoonEvents(){
            $stmt = $this->db->prepare("SELECT * FROM eventi, categorie, utenti WHERE data_avvenimento > CURDATE() AND categoria = id_categoria AND id_utente = organizzatore AND evento_attivo = TRUE ORDER BY data_avvenimento ASC LIMIT 3");
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }

        public function getNewEvents(){
            $stmt = $this->db->prepare("SELECT * FROM eventi, categorie, utenti WHERE categoria = id_categoria AND id_utente = organizzatore AND evento_attivo = TRUE ORDER BY data_inserimento DESC LIMIT 3");
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }

        public function getTrendEvents(){
            $stmt = $this->db->prepare("SELECT * FROM eventi, categorie, utenti WHERE categoria = id_categoria AND id_utente = organizzatore AND evento_attivo = TRUE ORDER BY numero_visualizzazioni DESC LIMIT 3");
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }

        public function getSuggestedEvents(){
            $stmt = $this->db->prepare("SELECT * FROM eventi, categorie, utenti WHERE categoria = id_categoria AND id_utente = organizzatore AND evento_attivo = TRUE AND id_categoria IN (SELECT id_categoria FROM utente_ha_categoria WHERE id_utente = ?) ORDER BY data_inserimento DESC LIMIT 3");
            $stmt->bind_param("i", $_SESSION["id_utente"]);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }
        
        public function getNearEvents(){
            $stmt = $this->db->prepare("SELECT * FROM eventi, categorie, utenti WHERE categoria = id_categoria AND id_utente = organizzatore AND evento_attivo = TRUE AND città = luogo_avvenimento AND luogo_avvenimento IN (SELECT città FROM utenti WHERE id_utente = ?) ORDER BY data_inserimento DESC LIMIT 3");
            $stmt->bind_param("i", $_SESSION["id_utente"]);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }

        public function getRandomEvents($n = 2){
            $stmt = $this->db->prepare("SELECT * FROM eventi, categorie, utenti WHERE categoria = id_categoria AND id_utente = organizzatore AND evento_attivo = TRUE ORDER BY RAND() LIMIT ?");
            $stmt->bind_param("i", $n);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }

        public function getEventsFromQuery($title, $city, $min_price, $max_price, $availability, $start_date, $end_date, $categories, $manager, $order){
            $query = "SELECT * FROM eventi, categorie, utenti WHERE categoria = id_categoria AND id_utente = organizzatore";
            $params = array();
            $params_to_bind = "";
            if(!isUserLoggedMe($this->getRootId())){
                $query = $query." AND evento_attivo = TRUE";
            }

            if($title != ""){
                $query = $query." AND titolo LIKE ?";
                array_push($params, $title);
                $params_to_bind = $params_to_bind."s";
            }

            if($city != ""){
                $query = $query." AND luogo_avvenimento = ?";
                array_push($params, $city);
                $params_to_bind = $params_to_bind."s";
            }
            $query = $query." AND prezzo BETWEEN ? AND ?";
            array_push($params, $min_price);
            $params_to_bind = $params_to_bind."i";
            array_push($params, $max_price);
            $params_to_bind = $params_to_bind."i";

            if($availability != -1){
                $query = $query." AND ? <=  (SELECT COUNT(id_biglietto) as numero_biglietti FROM biglietti WHERE id_evento = evento AND proprietario = organizzatore)";
                array_push($params, $availability);
                $params_to_bind = $params_to_bind."i";
            }

            $query = $query." AND data_avvenimento > ?";
            array_push($params, $start_date);
            $params_to_bind = $params_to_bind."s";

            if($end_date != 0){
                $query = $query." AND data_avvenimento < ?";
                array_push($params, $end_date);
                $params_to_bind = $params_to_bind."s";
            }
            if(count($categories) != 0){
                $query = $query." AND (";
                foreach($categories as $categoria){
                    $query = $query." categoria = ? OR";
                    array_push($params, $categoria);
                    $params_to_bind = $params_to_bind."i";
                }
                $query = $query.")";
            }
            $query = str_replace(" OR)", ")", $query);

            if($manager != ""){
                $query = $query." AND organizzatore = ?";
                array_push($params, $manager);
                $params_to_bind = $params_to_bind."i";
            }

            switch($order){
                case "dc":
                    $query = $query." ORDER BY data_avvenimento ASC";
                    break;
                case "dd":
                    $query = $query." ORDER BY data_avvenimento DESC";
                    break;
                case "pc":
                    $query = $query." ORDER BY prezzo ASC";
                    break;
                case "pd":
                    $query = $query." ORDER BY prezzo DESC";
                    break;
                default: 
                    $query = $query." ORDER BY data_avvenimento ASC";
            }
            $stmt = $this->db->prepare($query);
            $stmt->bind_param($params_to_bind, ...array_values($params));
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }

        public function addEvent($title, $city, $address, $img, $start_date, $end_date, $category, $long_desc, $price, $time){
            if($img === ""){
                $img = "event_default.jpg";
            }
            if($end_date == null){
                $query = "INSERT INTO `eventi`(`id_evento`, `categoria`, `titolo`, `luogo_avvenimento`, `indirizzo`, `data_avvenimento`, `data_conclusione`, `orario`, 
                `descrizione_lunga`, `prezzo`, `immagine_evento`, `numero_visualizzazioni`, `organizzatore`, `data_inserimento`, `evento_attivo`)
                 VALUES (null, ?, ?, ?, ?, ?, null, ?, ?, ?, ?, 0, ?, CURDATE(), TRUE)";
                $stmt = $this->db->prepare($query);
                $stmt->bind_param("issssssisi", $category, $title, $city, $address, $start_date, $time, $long_desc, $price, $img, $_SESSION["id_utente"]);     
            } else {
                $query = "INSERT INTO `eventi`(`id_evento`, `categoria`, `titolo`, `luogo_avvenimento`, `indirizzo`, `data_avvenimento`, `data_conclusione`, `orario`, 
                `descrizione_lunga`, `prezzo`, `immagine_evento`, `numero_visualizzazioni`, `organizzatore`, `data_inserimento`, `evento_attivo`)
                 VALUES (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?, CURDATE(), TRUE)";
                $stmt = $this->db->prepare($query);
                $stmt->bind_param("isssssssisi", $category, $title, $city, $address, $start_date, $end_date, $time, $long_desc, $price, $img, $_SESSION["id_utente"]);
            }
            $stmt->execute();
            return $stmt->insert_id;
        }

        public function addUserPreferences($id_utente, $categories){
            foreach($categories as $category){
                $stmt = $this->db->prepare("INSERT INTO utente_ha_categoria(utente, categoria) VALUES (?, ?)");
                $stmt->bind_param("ii", $id_utente, $category);
                $stmt->execute();
            }
        }

        public function addTicketsForEvent($event_id, $n){
            for($i = 1; $i <= $n; $i++){
                $stmt = $this->db->prepare("INSERT INTO biglietti(id_biglietto, evento, proprietario) VALUES (?, ?, ?)");
                $stmt->bind_param("iii", $i, $event_id, $_SESSION["id_utente"]);
                $stmt->execute();
            }
        }

        public function getNotBoughtTickets($n, $id_evento){
            $stmt = $this->db->prepare("SELECT * FROM biglietti, eventi WHERE evento = id_evento AND id_evento = ? AND proprietario = organizzatore LIMIT ?");
            $stmt->bind_param("ii", $id_evento, $n);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }

        public function addUserTickets($tickets){
            foreach($tickets as $ticket){
                $stmt = $this->db->prepare("UPDATE biglietti SET proprietario = ? WHERE id_biglietto = ? AND evento = ?");
                $stmt->bind_param("iii", $_SESSION["id_utente"], $ticket["ticket_id"], $ticket["event_id"]);
                $stmt->execute();
            }
        }

        public function getUserTicketsOfEvent($id_utente, $event_id){
            $stmt = $this->db->prepare("SELECT * FROM biglietti WHERE proprietario = ? AND evento = ?");
            $stmt->bind_param("ii", $id_utente, $event_id);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }

        public function refundTickets($tickets, $manager, $number){
            $i = 0;
            foreach($tickets as $ticket){
                if($i < $number) {
                    $stmt = $this->db->prepare("UPDATE biglietti SET proprietario = ? WHERE proprietario = ? AND id_biglietto = ? AND evento = ?");
                    $stmt->bind_param("iiii", $manager, $_SESSION["id_utente"], $ticket["id_biglietto"], $ticket["evento"]);
                    $stmt->execute();
                }
                $i++;
            }
        }

        public function getUserLastNotificationId($user_id){
            $stmt = $this->db->prepare("SELECT id_notifica_per_utente FROM utente_riceve_notifiche WHERE utente = ? ORDER BY id_notifica_per_utente DESC LIMIT 1");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            return count($result) == 0 ? 0 : $result[0]["id_notifica_per_utente"];
        }

        public function sendNotificationToUser($user_id, $msg, $notification_type){
            $notification_id = $this->getUserLastNotificationId($user_id) + 1;
            $stmt = $this->db->prepare("INSERT INTO utente_riceve_notifiche(utente, id_notifica_per_utente, id_notifica, messaggio_finale, timestamp) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP())");
            $stmt->bind_param("iiis", $user_id, $notification_id, $notification_type, $msg);
            $stmt->execute();
            mysqli_error($this->db);
        }

        public function getUserNotifications(){
            $stmt = $this->db->prepare("SELECT * FROM utente_riceve_notifiche WHERE utente = ?");
            $stmt->bind_param("i", $_SESSION["id_utente"]);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }

        public function getNotificationPieces($id_notifica){
            $stmt = $this->db->prepare("SELECT * FROM bozze_notifiche WHERE id_notifica = ?");
            $stmt->bind_param("i", $id_notifica);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }

        public function updateEvent($event_id, $category, $title, $city, $address, $start_date, $end_date, $time, $long_desc, $image){
            $stmt = $this->db->prepare("UPDATE eventi SET categoria = ?, titolo = ?, luogo_avvenimento = ?, indirizzo = ?, data_avvenimento = ?, data_conclusione = ?, orario = ?, descrizione_lunga = ?, immagine_evento = ? WHERE id_evento = ? AND organizzatore = ?");
            $stmt->bind_param("issssssssii", $category, $title, $city, $address, $start_date, $end_date, $time, $long_desc, $image, $event_id, $_SESSION["id_utente"]);
            $stmt->execute();
        }

        public function updateUser($email, $password, $city, $phone, $profile_image, $username){
            if($password === ""){
                $stmt = $this->db->prepare("UPDATE utenti SET  email = ?, città = ?, cellulare = ?, immagine = ?, username = ? WHERE id_utente = ?");
                $stmt->bind_param("sssssi", $email, $city, $phone, $profile_image, $username, $_SESSION["id_utente"]);
            } else {
                $password = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $this->db->prepare("UPDATE utenti SET  email = ?, password = ?, città = ?, cellulare = ?, immagine = ?, username = ? WHERE id_utente = ?");
                $stmt->bind_param("ssssssi", $email, $password, $city, $phone, $profile_image, $username, $_SESSION["id_utente"]);
            }
            $stmt->execute();
        }

        public function updateUserPreferences($old_categories, $categories){
            foreach($old_categories as $category){
                $stmt = $this->db->prepare("DELETE FROM utente_ha_categoria WHERE utente = ? AND categoria = ?");
                $stmt->bind_param("ii", $_SESSION["id_utente"], $category);
                $stmt->execute();
            }
            $this->addUserPreferences($_SESSION["id_utente"], $categories);
        }

        public function deleteUser($user_id){
            $stmt = $this->db->prepare("UPDATE utenti SET utente_attivo = FALSE WHERE id_utente = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
        }

        public function deleteEvent($event_id){
            $stmt = $this->db->prepare("UPDATE eventi SET evento_attivo = FALSE WHERE id_evento = ?");
            $stmt->bind_param("i", $event_id);
            $stmt->execute();
        }

        public function checkUsername($username){
            $stmt = $this->db->prepare("SELECT username FROM utenti WHERE username = ? AND id_utente != ?");
            $stmt->bind_param("si", $username, $_SESSION["id_utente"]);
            $stmt->execute();
            return count($stmt->get_result()->fetch_all(MYSQLI_ASSOC)) == 0;
        }

        public function updateViews($event_id){
            $stmt = $this->db->prepare("UPDATE eventi SET numero_visualizzazioni = numero_visualizzazioni + 1 WHERE id_evento = ?");
            $stmt->bind_param("i", $event_id);
            $stmt->execute();
        }

        public function enableUser($user_id){
            $stmt = $this->db->prepare("UPDATE utenti SET utente_attivo = TRUE WHERE id_utente = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
        }

        public function enableEvent($event_id){
            $stmt = $this->db->prepare("UPDATE eventi SET evento_attivo = TRUE WHERE id_evento = ?");
            $stmt->bind_param("i", $event_id);
            $stmt->execute();
        }

        public function getUserWithEventTickets($event_id){
            $stmt = $this->db->prepare("SELECT proprietario, organizzatore FROM biglietti, eventi WHERE organizzatore != proprietario AND evento = ? AND id_evento = evento GROUP BY proprietario, organizzatore");
            $stmt->bind_param("i", $event_id);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }

        public function getAllEvents(){
            $stmt = $this->db->prepare("SELECT titolo FROM eventi WHERE evento_attivo = TRUE");
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }

        public function getSoldTickets($event_id){ // da fare
            $stmt = $this->db->prepare("SELECT COUNT(*) as Numero FROM eventi, biglietti WHERE evento_attivo = TRUE AND evento = ? AND evento = id_evento AND proprietario != organizzatore ");
            $stmt->bind_param("i", $event_id);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0]["Numero"];
        }
    }
?>