<?php
// Sessoon starten
session_start();

// Modul auslesen
$mod = $_GET['mod'];

if($mod == '/phpinfo')
	die(phpinfo());

// Falls kein Modul gewählt wurde (/index.php) auf /home verweisen
if($mod == '/index.php' || $mod == '/home') {
	header('Location: http://'.$_SERVER['HTTP_HOST'].'/desk');
	die('Header-Redirect');
}

// Hauptverarbeitung includen
include 'src/core.inc.php';

?>