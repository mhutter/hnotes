<?php
/********************************/
/* Funktionen für gPROFILE.net	*/
/* Autor: Manuel Hutter			*/
/********************************/

#	Umlaute Ersetzen usw
function clean_xhtml($s)
{
	$s = str_replace('ä', '&auml;', $s);
	$s = str_replace('Ä', '&Auml;', $s);
	$s = str_replace('ö', '&ouml;', $s);
	$s = str_replace('Ö', '&Ouml;', $s);
	$s = str_replace('ü', '&uuml;', $s);
	$s = str_replace('Ü', '&Uuml;', $s);
	
	$s = str_ireplace('<br>', '<br/>', $s);
	$s = str_ireplace('<hr>', '<hr/>', $s);
	
	return $s;
}
###


#	Einfache Query absetzen
function ex_sql($query, &$msg)
{
	$result = mysql_query($query);
	if (!$result) {die('Ungültige Abfrage: '.mysql_error());}
	
	$msg = $result;
	
	return true;
}
###


#	Lorem Ipsum generieren ;D
function generate_lipsum($anz=5)
{
	$path = "http://lipsum.com/feed/html?amount={$anz}&what=paras&start=yes";
	$page = file_get_contents($path);
	$pattern = '/<div id="lipsum">(.+)<\/div>/imsU';
	preg_match($pattern, $page, $treffer);
	return $treffer[1];
}
###


#	Lokaler header-Redirect
function redirect($path)
{
	$host  = $_SERVER['HTTP_HOST'];
	header("Location: http://$host/$path");
	die('Redirect');
}
###


#	BB-Codes parsen
function parse_bb($s)
{
	#	Schriftformatierungen
	// [b]
	$pattern = '/\[b\](.*)\[\/b\]/isU';
	$replace = '<b>\\1</b>';
	$s = preg_replace($pattern, $replace, $s);
	
	// [i]
	$pattern = '/\[i\](.*)\[\/i\]/isU';
	$replace = '<i>\\1</i>';
	$s = preg_replace($pattern, $replace, $s);
	
	// [b]
	$pattern = '/\[u\](.*)\[\/u\]/isU';
	$replace = '<span style="text-decoration: underline;">\\1</span>';
	$s = preg_replace($pattern, $replace, $s);

	// [color=]
	$pattern = '/\[color=(.+)\](.*)\[\/color\]/isU';
	$replace = '<span style="color:$1">$2</span>';
	$s = preg_replace($pattern, $replace, $s);
	###
	
	
	#	Links
	// ohne tags
	$pattern = '/(^|[\s])(http|http[s]*|ftp|file):\/\/([^\s]+)/i';
	$replace = '\\1<a href="\\2://\\3">\\2://\\3</a>';
	$s = preg_replace($pattern, $replace, $s);
	
	// [url]
	$pattern = '/\[url\](.*)\[\/url\]/isU';
	$replace = '<a href="\\1">\\1</a>';
	$s = preg_replace($pattern, $replace, $s);
	
	// [url=]
	$pattern = '/\[url=(.*)\](.*)\[\/url\]/isU';
	$replace = '<a href="\\1">\\2</a>';
	$s = preg_replace($pattern, $replace, $s);
	###
	
	
	#	Bilder
	// [img]
	$pattern = '/\[img](.*)\[\/img\]/isU';
	$replace = '<img src="\\1" alt="Bild"/>';
	$s = preg_replace($pattern, $replace, $s);
	###
	
	
	
	// Ende
	return $s;
}
###






?>