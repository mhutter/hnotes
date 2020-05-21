<?php
/************************************/
/* Liste für hNotes					*/
/* Autor: Manuel Hutter				*/
/************************************/
// $c["titel"]	= $c["titel"];	// Seitentitel

$teile	= explode('/', $argumente, 2);
$nid	= $teile[0];
$argumente	= $teile[1];


if ($nid == 'new') {		// Neuer Eintrag anlegen
	$x = mktime();
	$sql	= "INSERT INTO notes_items VALUES (null, '{$uid}', 'Titel', 'Content', '1', '{$x}', '{$x}')";
	$result	= mysql_query($sql) or die('Ungültige Abfrage: ' . mysql_error());
	redirect('edit/'.mysql_insert_id());
}
elseif($_POST['sender'])	// Eintrag speichern
{
	$ntitle		= htmlentities( utf8_decode($_POST['ntitle']) );
	$ncontent	= htmlentities( utf8_decode($_POST['ncontent']) );
	$nprio		= $_POST['nprio'];
	$ndate		= mktime();
	
	$sql = "UPDATE notes_items SET n_title='{$ntitle}', n_content='{$ncontent}', n_prio='{$nprio}', n_lastmod='{$ndate}' WHERE n_id='{$nid}' AND u_id='{$uid}'";
	ex_sql($sql, $void);
}

//$c["main"] = "<p>$ntitle</p><p>$ncontent</p>";

// Eintrag auslesen und Formular ausgeben
$sql	= "SELECT * FROM notes_items WHERE n_id='{$nid}' AND u_id='{$uid}' LIMIT 0,1";
$result	= mysql_query($sql) or die('Ungültige Abfrage: ' . mysql_error());

if(mysql_num_rows($result)<1)  {
	$c["main"]	.= "\t\t<p style=\"color: #ff0000;\">$loc[502]</p>\n";
} else
{
	$row = mysql_fetch_array($result);
	$title		= $row["n_title"];
	$content	= $row['n_content'];
	$prio		= $row['n_prio'];
	
$priodropdown = <<<EOT
	<select name="nprio" size="1">
		<option value="2" class="prio wichtig">$loc[112]</option>
		<option value="1" class="prio normal">$loc[111]</option>
		<option value="0" class="prio unwichtig">$loc[110]</option>
	</select>
EOT;

$priodropdown = str_ireplace('value="'.$prio.'"', 'value="'.$prio.'" selected="selected"', $priodropdown);
	
	$c['main']	.= <<<EOT
<form action="/edit/{$nid}" method="post">
<p>
	<a href="/desk">[&laquo;] $loc[403]</a>
	&nbsp;&nbsp;|&nbsp;&nbsp;	
	<a href="/view/{$nid}">$img_x $loc[404]</a>
	&nbsp;&nbsp;|&nbsp;&nbsp;
	<a href="/delete/{$nid}">$img_minus {$loc[501]}</a>
	&nbsp;&nbsp;&nbsp;
	<input type="submit" name="sender" value="&nbsp;&nbsp;&nbsp;{$loc[400]}&nbsp;&nbsp;&nbsp;" style="font-weight: bold;"/>
</p>
<table style="width: 100%;"><tr><td>
$priodropdown
</td>
<td style="width: 100%;">
	<input type="text" name="ntitle" tabindex="1" style="width: 100%;" value="$title"/>
</td></tr></table>
<p>
	<textarea tabindex="2" name="ncontent" style="width:100%; height:400px">$content</textarea>
</p>
</form>

EOT;
}

?>