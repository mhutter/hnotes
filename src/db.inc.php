<?php

#	Param
$server	= 'localhost';
$user	= 'hbombch_notes';
$pass	= 'xxx';
$db		= 'hbombch_hutter';
###

mysql_connect($server, $user, $pass)
	or die('<h1>DB-Fehler</h1><br>Fehler beim Verbinden:<hr>'.mysql_error().'<hr>');

mysql_select_db($db)
	or die('DB konnte nicht ausgew채hlt werden');

unset($server, $user, $pass, $db);

?>
