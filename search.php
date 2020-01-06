<?php
    require_once("template/boot.php");

    $templateParams["title"] = "Italia's Got Events - Risultati della ricerca";
    $templateParams["name"] = "search_base.php";
    $templateParams["js"] = ["animations.js", "checkErrors.js"];

    $title = isset($_GET["title"]) ? $_GET["title"] : "";
    $city = isset($_GET["cities"]) ? $_GET["cities"] : "";
    $availability = isset($_GET["tickets"]) ? $_GET["tickets"] : -1;
    $min_price = isset($_GET["min_price"]) ? $_GET["min_price"] : 0;
    $max_price = isset($_GET["max_price"]) ? $_GET["max_price"] : $dbh->getMaxPrice();
    $start_date = isset($_GET["start_date"]) ? $_GET["start_date"] : date("Y-m-d");
    $end_date = isset($_GET["check_more_days"]) ? $_GET["end_date"] : "";
    $manager = isset($_GET["manager"]) ? $_GET["manager"] : 0;
    $order = isset($_GET["order"]) ? $_GET["order"] : "dc";
    
    $categories = array();
    foreach(array_keys($_GET) as $key){
        if(substr($key, 0, strlen("category_")) === "category_"){
            $category = str_replace("category_", "", $key);
            array_push($categories, $category);
        }
    }
    if(isset($_GET["check_not_available"])){
        $availability = 1;
    }

    $events = $dbh->getEventsFromQuery($title, $city, $min_price, $max_price, $availability, $start_date, $end_date, $categories, $manager, $order);
    if(isset($_GET["action"])){
        switch($_GET["action"]){
            case 0:
                $events = $dbh->getTrendEvents();
                break;
            case 1:
                $events = $dbh->getSoonEvents();
                break;
            case 2:
                $events = $dbh->getFreeEvents();
                break;
            case 3:
                $events = $dbh->getNewEvents();
                break;
            case 4:
                $events = $dbh->getSuggestedEvents();
                break;
            case 5:
                $events = $dbh->getNearEvents();
                break;
            default:
                header("location: 404.php");
                die();
        }
    }
    require("template/base.php");
?>