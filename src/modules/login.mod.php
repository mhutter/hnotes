<?php
/************************************/
/* Beispielmodul für gPROFILE.net	*/
/* Autor: Manuel Hutter				*/
/************************************/

if($logged_in)
	redirect('desk');

$c_template	= 'box';
$c["width"] = "400px";
$c["titel"]	= $c["titel"].' - Login';	// Seitentitel

if(!$logged_in) {
	if($_POST['nlogin'] || $_POST['npass'])
		$msg = '<span style="font-size: 8pt; color: #ff0000">'.$loc[200].'</span>';
}
$nlogin = strlen($_SESSION['notes_firstlog'])>0 ? $_SESSION['notes_firstlog'] : $nlogin;
unset($_SESSION['notes_firstlog']);

// Browser
$browsermsg = '&nbsp;';
if(stristr($_SERVER["HTTP_USER_AGENT"], 'Gecko') === FALSE)
	$browsermsg = $loc[202].' <a href="'.$loc[203].'" title="FireFox2!"><img src="/_pub/img/ff_button.gif" alt="FireFox2!"/></a>';


#	MAIN-Content... Beschreibung der Seite etc...
$c["main"]	= '<h1><a href="/" class="title">hNotes</a></h1>';
$c["main"]	.= <<<EOT
<h2>Login</h2>
<form action="/login" method="post">
<p>
$msg
</p>

<table style="text-align:right; border-spacing:4px; margin-left:auto; margin-right:auto;"><tr>
	<td style="width:50%;"><label for="nlogin">$loc[2]</label></td>
	<td style="width:50%;"><input tabindex="1" name="nlogin" id="nlogin" type="text" size="20" value="$nlogin" class="textbox" /></td>
</tr><tr>
	<td><label for="npass">$loc[3]</label></td>
	<td><input tabindex="2" name="npass" id="npass" type="password" size="20" class="textbox" /></td>
</tr><tr>
	<td><label for="nstay">$loc[204]</label></td>
	<td style="text-align:left;"><input type="checkbox" name="nstay" id="nstay" tabindex="3"/></td>
</tr><tr>
	<td>
		$browsermsg
	</td>
	<td style="text-align: right;">
		<input tabindex="99" type="submit" value="$loc[4]"/>
	</td>
</tr></table>

</form>
<hr/>
<p>
	<a href="/register" title="zur Registrierung">&raquo; $loc[201]</a>
</p>
<hr/>
<h2>News</h2>

<p><b>Update 03.05.2007</b><br/>
Udate!
Die Notizbearbeitung wurde überarbeitet:<br/>
- Mehr Buttons :P<br/>
- BBcodes! (Anleitung folgt)
</p>

<p><b>Erste fertige Version online</b><br/>
Nach einigen Tagen Entwicklung ist nun die erste fertige Version online.<br/>
- Registrierung, Login, logout :P<br/>
- Erstellen, bearbeiten und löschen von Notizen<br/>
</p>

EOT;
###

?>