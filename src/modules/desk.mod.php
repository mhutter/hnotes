<?php
/************************************/
/* Liste für hNotes					*/
/* Autor: Manuel Hutter				*/
/************************************/

$c["titel"]	.= ' - Desktop';

#	MAIN-Content...
$c["main"]	= <<<EOT
	<h1>$loc[100]</h1>
	<p>
	<a href="/edit/new">$img_plus $loc[101]</a>
	</p>
	<hr/>
<table class="liste">
	<tr>
		<th class="prio">!</th>
		<th class="title">Titel<!-- - <span style="color:#cccccc;">Vorschau</span></th>-->
		<th class="lastmod">Zuletzt bearbeitet</th>
		<th class="created">Erstellt</th>
	</tr>

EOT;
$sql	= "SELECT * FROM notes_items WHERE u_id='{$uid}' ORDER BY n_id ASC";
$result	= mysql_query($sql);
while($row = mysql_fetch_array($result)) {
	$x = $x=='a' ? 'b' : 'a';
	$id			= $row["n_id"];
	$title		= $row["n_title"];
/*	$preview	= preg_replace('/\[.+\]/isU', '', $row['n_content']);
	$teile		= explode("\n", $preview, 2);
	$preview	= $teile[0];*/
	$prio		= $img_prio[$row['n_prio']];
	$lastmod	= date('d.m.y, h:i', $row['n_lastmod']);
	$create		= date('d.m.y', $row['n_createdate']);
	
	

	$c['main'] .= <<<EOT
	<tr class="$x" onclick="location.href='/view/$id';">
		<td class="prio">$prio</td>
		<td class="title">
			<div class="wrapbox"><span style="font-weight:bold;">$title</span><!--<span style="color:#aaaaaa;"> - $preview</span>--></div>
		</td>
		<td class="lastmod">$lastmod</td>
		<td class="created">$create</td>
	</tr>

EOT;
	
}

$c["main"]	.= '</table>';
###

?>