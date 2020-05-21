<?php
/************************************/
/* Modul für hNotes					*/
/* Autor: Manuel Hutter				*/
/************************************/

$c_template	= 'box';
$c["width"] = "400px";
$c["titel"]	.= ' - Löschen';

$teile	= explode('/', $argumente, 2);
$nid	= $teile[0];
$argumente	= $teile[1];


// Refer-Check
$valid_refers = array('notes.h-bomb.ch',
					'localhost',
					'pc-eis-hum', 
					'zeus',
					'h-bomb.dyndns.org');
$urlinfo = parse_url($_SERVER["HTTP_REFERER"]);
$ref_host = $urlinfo["host"];

if(!in_array($ref_host, $valid_refers))
{
	$c['main'] = $loc[600];
}
else
{
	// Löschung schon bestätigt?
	if($_POST['bestaetigung'] == 'ok')
	{
		$sql = "DELETE FROM notes_items WHERE n_id='{$nid}' AND u_id='{$uid}' LIMIT 1";
		ex_sql($sql, $void);
		redirect('desk');
	}
	else	// Ansonsten Formular anzeigen
	{
		$c['main'] = <<<EOT
<h1><a href="/" class="title">hNotes</a></h1>
<form action="/delete/{$nid}" method="post">
<p>
	<span class="warnung"><img src="/_pub/img/info_red.png" alt="Info"/> $loc[601]</span><br/>
	<br/>
	<a href="/view/{$nid}">$img_x $loc[404]</a><br/>
	<br/>
	<input type="hidden" name="bestaetigung" value="ok"/>
	<input type="submit" value="$loc[602]">
</p>
</form>

EOT;
	}
}

?>