<?php
/************************************/
/* Liste für hNotes					*/
/* Autor: Manuel Hutter				*/
/************************************/

$c['titel']	.= ' - Statistiken';

$c_template	= 'box';
$c['width'] = '400px';


$format = 'D, j. M Y, H:i T';

// Anzahl Registrierter User
$q = 'SELECT count(u_id) FROM notes_users WHERE u_id<>1';
$result = mysql_query($q);
$row = mysql_fetch_array($result);
$anz_users = $row[0];

// Letzter Registrierte UID
$q = 'SELECT u_id FROM notes_users WHERE u_id<>1 ORDER BY u_regdate DESC LIMIT 0,1';
$result = mysql_query($q);
$row = mysql_fetch_array($result);
$last_uid = $row[0];

// Registrationsdatum
$q = "SELECT u_regdate FROM notes_users WHERE u_id='$uid'";
$result = mysql_query($q);
$row = mysql_fetch_array($result);
$userregged = date($format, $row[0]);

// Anzahl Notizen
$q = 'SELECT count(n_id) FROM notes_items';
$result = mysql_query($q);
$row = mysql_fetch_array($result);
$anz_notizen = $row[0];

// Höchste Notiz-ID
$q = 'SELECT n_id FROM notes_items ORDER BY n_id DESC LIMIT 0,1';
$result = mysql_query($q);
$row = mysql_fetch_array($result);
$max_id = $row[0];

// Neueste Notiz
$q = 'SELECT n_id, n_createdate FROM notes_items ORDER BY n_createdate DESC LIMIT 0,1';
$result = mysql_query($q);
$row = mysql_fetch_array($result);
$letzte = $row[0];
$letzte_d = date($format, $row[1]);

// Aktuellste Notiz
$q = 'SELECT n_id, n_lastmod FROM notes_items ORDER BY n_lastmod DESC LIMIT 0,1';
$result = mysql_query($q);
$row = mysql_fetch_array($result);
$aktuellste = $row[0];
$aktuellste_d = date($format, $row[1]);


$c['main'] = <<<EOT
	<h1><a href="/" class="title">hNotes</a></h1>
	<h2>Statistiken</h2>
	<p>Anzahl Registrierter User: <b>$anz_users</b><br/>
	Letzte registrierte UID: <b>$last_uid</b></p>
	
	<p>Du bist User <b>$logged_as</b><br/>
	Registriert am: <b>$userregged</b></p>
	
	<p>Anzahl Notizen: <b>$anz_notizen</b><br/>
	Höchste Notiz-ID: <b>$max_id</b></p>
	
	<p>Zuletzt erstellte Notiz: <b>$letzte</b><br/>
	Wann? <b>$letzte_d</b></p>
	
	<p>Zuletzt gespeicherte Notiz: <b>$aktuellste</b><br/>
	Wann? <b>$aktuellste_d</b></p>

EOT;
?>