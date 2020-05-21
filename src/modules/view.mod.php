<?php
/************************************/
/* Liste für hNotes					*/
/* Autor: Manuel Hutter				*/
/************************************/

$c["titel"]	= $c["titel"] . ' - Notiz anzeigen';

$teile	= explode('/', $argumente, 2);
$nid	= $teile[0];
$argumente	= $teile[1];

#	MAIN-Content...

$sql	= "SELECT * FROM notes_items WHERE n_id='{$nid}' AND u_id='{$uid}' LIMIT 0,1";
$result	= mysql_query($sql) or die('Ungültige Abfrage: ' . mysql_error());

if(mysql_num_rows($result)<1)  {
	$c["main"]	.= "\t\t<p class=\"warnung\">$img_info_red $loc[502]</p>\n";
} else {
	$row = mysql_fetch_array($result);
	$title = $row["n_title"];
	$content = parse_bb( $row['n_content'] );
	$content = nl2br( $content );
	

	$c["main"] .= "\t\t".'<a href="/desk">&laquo; '.$loc[403].'</a>';
	$c["main"] .= '&nbsp;&nbsp;|&nbsp;&nbsp;';
	$c["main"] .= '<a href="/edit/'.$nid.'">'.$img_edit.' '.$loc[5].'</a>';
	$c["main"] .= '&nbsp;&nbsp;|&nbsp;&nbsp;';
	$c["main"] .= '<a href="/delete/'.$nid.'">'.$img_minus.' '.$loc[501].'</a>'."\n";
	
	$c["main"]	.= "\t\t<hr />";
	$c["main"]	.= "\t\t<h2>{$title}</h2>\n";
	$c["main"]	.= "\t\t<p>{$content}</p>";
}
###

?>