<?php
$hNotesVer = '0.9.5.1';
/************************************/
/* Core-Script für hNotes			*/
/* Autor: Manuel Hutter				*/
/************************************/

#	Includes
include 'vars.inc.php';			// Variablen
include 'functions.inc.php';	// Funktionsfile
include 'db.inc.php';			// DB-Connect
###

#	$mod
$treffer	= explode('/', $mod, 3);
$mod		= strtolower($treffer[1]);
$argumente	= $treffer[2];
###


#	Login
include 'login.inc.php';

if(!$logged_in && 			// Module die man ohne Login sehen darf!
	$mod!='impressum' && 
	$mod!='about' && 
	$mod!='login' && 
	$mod!='register')
		redirect('login');
###


#	Sprachen
// Alle Sprachen auslesen und in array schreiben
$files = scandir('src/localisation');
foreach($files as $fn) {
	if($fn!='.' && $fn!='..') {
		$teil = explode('.', $fn, 3);
		$sprachen[$teil[0]] = $teil[1];		// z.B. $sprache["de"] = "Deutsch"
	}
}

// Sessions-Var abgreifen
$sprache = $_SESSION["hbb_notes_lang"] ? $_SESSION["hbb_notes_lang"] : false;

// GET-Var abgreifen
if(strlen($_GET["setlang"]) > 0) {
	if(array_key_exists($_GET["setlang"], $sprachen))
		$sprache = $_GET["setlang"];
}

// Falls keine Einstellungen gefunden
if(!$sprache) {
	// Browser-Standard verwenden
	$str = strtolower(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2));
	if(array_key_exists($str, $sprachen)) {
		$sprache = $str;
	} else {
		// Oder Standard-Sprache
		$sprache = 'de';		// Standard-Sprache
	}
}

// Aktuelle Sprache speichern
$_SESSION["hbb_notes_lang"] = $sprache;

include_once "localisation/{$sprache}.{$sprachen[$sprache]}.loc.php";

if($sprache == 'en') {
	$sprachwahl = '<a href="?setlang=de" title="Deutsch">DE</a>, <b>EN</b>';
} elseif($sprache == 'de') {
	$sprachwahl = '<b>DE</b>, <a href="?setlang=en" title="English">EN</a>';
} else {
	$sprachwahl = '<a href="?setlang=de" title="Deutsch">DE</a>, <a href="?setlang=en" title="English">EN</a>';
}
$c["lang"] = $sprachwahl;
###



#	Defaults und Config
$c["titel"]		= 'hNotes';
$c_template		= 'main';
$c["footer"]	= 'hNotes v'.$hNotesVer.' - Code &amp; Design &copy;2007 by Manuel Hutter (manuel at h-bomb.ch) - <a class="footer" href="/about">'.$loc[1].'</a>';
###


#	BAR
include_once 'bar.inc.php';
###


#	Modul
$modpath 	= "src/modules/{$mod}.mod.php";
if(!file_exists($modpath)) {
	$modpath = "modules/httperror.mod.php";
	$argumente = "404/{$mod}";
}
###

#	Modul includen
include $modpath;
###


#	Template anwenden
$tmplpfad	= "src/templates/{$c_template}.tmpl.xhtml";
if(!file_exists($tmplpfad))
	die('<h1>Fehler</h1><p>Ungültiges Template!</p>');
	
$template	= file_get_contents($tmplpfad);
$muster		= '/__([^_]+)__/i';
preg_match_all($muster, $template, $treffer, PREG_SET_ORDER);

foreach($treffer as $key => $val) {
	$template = str_replace($val[0], $c[$val[1]], $template);
}
###

echo clean_xhtml($template); // \m/

?>