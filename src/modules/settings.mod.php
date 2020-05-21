<?php

$c_template	= 'box';
$c["width"] = "400px";
$c['titel']	.= ' - '.$loc[700];

$changeinfo = '';

if($_POST) {
	
	$old = md5($_POST['oldpass']);
	$new1 = strlen($_POST['newpass1'])>0 ? md5($_POST['newpass1']) : 'onoz!';	// Überprüfung dass das Passwort nicht leer ist
	$new2 = md5($_POST['newpass2']);
	
	$sql = "SELECT * FROM notes_users WHERE u_id='{$uid}' AND u_pwd='{$old}'";
	$result = mysql_query($sql);
	$anz = mysql_num_rows($result);
	
	if($anz < 1) {	// Passwort falsch
		$changeinfo = $loc[705];
	} elseif($new1 !=  $new2) {	// Passwörter stimmen nicht überein
		$changeinfo = $loc[303];
	} else {
		
		$sql = "UPDATE notes_users SET u_pwd='$new1' WHERE u_id='{$uid}' LIMIT 1";
		ex_sql($sql, $void);
		
		//	Session und Cookie aktualisieren
		$_SESSION['npass']	= $new1;
		if($_COOKIE['npass'] == $old) {
			setcookie('npass', $new1, time()+60*60*24*365, '/', '', false, true);
		}
		
		$changeinfo = $loc[706];
	}
}

if(strlen($changeinfo)>0) {	// Kosmetik
	$changeinfo = '<img src="/_pub/img/info_red.png" alt="Info"/> '.$changeinfo;
}


$c['main'] = <<<EOT
<h1><a href="/" class="title">hNotes</a></h1>
<table class="form">
	<form action="/settings" method="post">
	<tr>
		<td colspan="2">
			<h2>$loc[701]</h2>
			<span class="warnung">$changeinfo</span>
		</td>
	</tr>
	<tr>
		<td>
			<label for="oldpass">$loc[702]</label>
		</td>
		<td>
			<input type="password" name="oldpass"/>
		</td>
	</tr>
	<tr>
		<td>
			<label for="newpass1">$loc[703]</label>
		</td>
		<td>
			<input type="password" name="newpass1"/>
		</td>
	</tr>
	<tr>
		<td>
			<label for="newpass2">$loc[704]</label>
		</td>
		<td>
			<input type="password" name="newpass2"/>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<input type="submit" value="$loc[701]"/>
		</td>
	</tr>
</table>
<br/><br/>
EOT;

?>