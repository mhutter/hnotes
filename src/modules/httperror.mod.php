<?php
/************************************/
/* Beispielmodul für h-bomb.ch		*/
/* Autor: Manuel Hutter				*/
/************************************/

// $c["head"]		= '<metatag>';	// Spezielle angaben im <head>
// $c["footer"]		= '&copy;2007 by Manuel Hutter';	// Seitenfooter
// $c["bar"]		= $c["bar"];	// Der Balken

// $c["titel"]	= $c["titel"];	// Seitentitel

$c_template	= 'httpstatus';	// Template Datei. Wird im pfad verwendet (z.B: "templates/{$c_template}.tmpl.htm})

#	MAIN-Content...

// $argumente zerteilen
$treffer	= explode('/', $argumente, 2);
$http_errno = $treffer[0];
$http_errarg = $treffer[1];

switch ($http_errno) {
	case "404":
		$c["titel"]	= "404 Not Found";
		$c["main"]	= <<<EOT
	<h1>404 Not Found</h1>
	<p>Der angeforderte Pfad <span style="font-family:monospace;">'/$http_errarg'</span> wurde nicht gefunden auf dem Server.</p>
	<p>The requested URL <span style="font-family:monospace;">'/$http_errarg'</span> was not found on this server.</p>
EOT;
		$c["main"] = str_ireplace('//', '/', $c["main"]);
		break;
}


$c["main"] .= "\n<hr>\n{$_SERVER["SERVER_SIGNATURE"]}";

###



?>