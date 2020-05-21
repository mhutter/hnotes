<?php
/************************************/
/* Beispielmodul für gPROFILE.net	*/
/* Autor: Manuel Hutter				*/
/************************************/

if($logged_in)
	redirect('desk');

$c_template	= 'box';
$c["width"] = "400px";
$c["titel"]	= $c["titel"].' - '.$loc[300];	// Seitentitel


#	MAIN-Content...
$regged = false;
if($_POST['rgesendet'] == "ja") {	// Falls Daten gesendet wurden
	$rlogin = $_POST['rlogin'];
	$rpass1 = $_POST['rpasseins'];
	$rpass2 = $_POST['rpasszwei'];
	
	if(strlen($rlogin)<1 || strlen($rpass1)<1 || strlen($rpass2)<1) { // Sind alle Felder gefüllt?
		$msg = '<span style="font-size:8pt; color:#ff0000;">'.$loc[304].'</span>';
	} elseif($rpass1 != $rpass2) {	// Stimmen die Passwörter überein?
		$msg = '<span style="font-size:8pt; color:#ff0000;">'.$loc[303].'</span>';
	} else {
		$query = "SELECT count(*) FROM notes_users WHERE u_login='$rlogin'";
		$result = mysql_query($query) or die('Ungültige Abfrage: '.mysql_error());
		$row = mysql_fetch_row($result);
		if($row[0] != '0') {	// Ist schon ein Benutzer mit 
			$msg = '<span style="font-size:8pt; color:#ff0000">'.$loc[306].'</span>';
		} else {
			// Account eintragen
			$rpass = md5($rpass1);
			$regdate = time();
			$query = "INSERT INTO notes_users (`u_login`, `u_pwd`, `u_regdate`) VALUES ('$rlogin', '$rpass', '$regdate')";
			ex_sql($query, $void);
			
			// Beispielnotiz eintragen:
			// UID abfragen
			$query = "SELECT u_id FROM notes_users WHERE u_login='$rlogin'";
			$result = mysql_query($query);
			if (!$result) {die('Ungültige Abfrage: ' . mysql_error());}
			$row = mysql_fetch_row($result);
			
			// Notiz erstellen
			$query = "INSERT INTO notes_items ( n_id , u_id , n_title , n_content ) VALUES (NULL , '$row[0]', '$loc[307]', '$loc[308]')";
			ex_sql($query, $void);
			
			// Bestätigung ausgeben
			$c["main"]	= '<h1><a href="/" class="blind">hNotes</a></h1>';
			$c["main"] .= '<p style="color:green;">'.$loc[305].'</p><hr/><p><a href="/login">&raquo; Login</a></p>';
			
			// Für Login
			$_SESSION['notes_firstlog'] = $rlogin;
			$regged = true;
		}
	}
	
}

if(!$regged) {	// falls Registrierung nicht beendet werden konnte
	
	$c["main"]	= '<h1><a href="/" class="title">hNotes</a></h1>';
	$c["main"]	.= <<<EOT
<form action="/register" method="post">
<p>
$msg
<input type="hidden" name="rgesendet" value="ja"/>
</p>
<table style="width: 100%;text-align:right;"><tr>
	<td>$loc[2]</td>
	<td><input tabindex="1" name="rlogin" type="text" size="20" value="$rlogin" class="textbox"/></td>
</tr><tr>
	<td colspan="2">&nbsp;</td>
</tr><tr>
	<td>$loc[3]</td>
	<td><input tabindex="2" name="rpasseins" type="password" size="20" class="textbox"/></td>
</tr><tr>
	<td>$loc[301]</td>
	<td><input tabindex="3" name="rpasszwei" type="password" size="20" class="textbox"/></td>
</tr><tr>
	<td colspan="2">&nbsp;</td>
</tr><tr>
	<td colspan="2" style="text-align:right;">
		<input tabindex="4" type="submit" value="$loc[302]"/>
		<input tabindex="5" type="reset" value="$loc[6]"/>
	</td>
</tr></table>
</form>
<br/>

EOT;

}

###

?>