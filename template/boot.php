<?php
session_start();
define("UPLOAD_DIR", "./upload/");
require_once("utils/functions.php");
require_once("utils/database.php");
$dbh = new DatabaseHelper("localhost", "root", "", "progetto");
?>