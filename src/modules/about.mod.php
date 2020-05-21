<?php
/************************************/
/* Beispielmodul für gPROFILE.net	*/
/* Autor: Manuel Hutter				*/
/************************************/

$c_template	= 'box';
$c["width"] = "600px";


$c["titel"]	= $c["titel"].' - About';	// Seitentitel

#	MAIN-Content... Beschreibung der Seite etc...
$c["main"]	= '<h1><a href="/" class="title">hNotes</a></h1>';
if($sprache == 'de') {
	$c["main"]	.= <<<EOT
<h1>Über hNotes</h1>
<a href="/impressum" title="Impressum">Impressum</a>
<h2>Was ist hNotes?</h2>
<p>
	<b>hNotes</b> ist ein einfacher Online-Notizblock. Sie können ihre Notizen von überall her aufrufen und verändern.<br/>
	Damit niemand ihre vertraulichen Notizen liest, sind diese Passwortgeschützt.
</p>
<h2>Entstehung</h2>
<p>
	<b>hNotes</b> enstand ursprünglich aus reinem Selbstzweck. Da ich häufig Notizen zwischen meinem Arbeitsplatz, der Schule und meinem Zuhause hin und her schicken musste, kam mir die Idee, einen kleinen Online-Notizblock zu entwickeln.<br/>
	So habe ich mich am Abend des 16. April 2007 daran gemacht, meine Ideen zu realisieren.	
</p>
<h2>Zukunft</h2>
<p>
	Folgende Dinge habe ich in Planung:
</p>
<ul>
	<li>Weitere Eigenschaften (Erstelldatum, letzte Bearbeitung, letzter Aufruf, wer hat die Notiz angesehen, usw...)</li>
	<li>Alternatives Design</li>
	<li>Freundeslisten</li>
	<li>Verschiedene Veröffentlichungsstufen für Notizen (Privat/Für Freunde/Öffentlich)</li>
	<li>Diversen AJAX-Krimskrams</li>
	<li>Versionshistorien</li>
</ul>
<p>
	Falls sie gerade eine heisse Idee haben, schreiben sie mir eine Mail an (notes [at] h-bomb.ch) !
</p>


EOT;
} else {
	$c["main"]	.= <<<EOT
<h1>About hNotes</h1>
<a href="/impressum" title="Impressum">Impressum</a>
<h2>What is hNotes?</h2>
<p>
	<b>hNotes</b> is a lightweight Online Notepad. You can view and edit your Notes from everywhere!<br/>
	Of course, all your Notes are secured by a Password!
</p>
<h2>Development</h2>
<p>
	<b>hNotes</b> was originally created for myself. I often transfered Notes between my Workplace, School and my Home. So I came up with the Idea, to create a little Online Notepad<br/>
	In April 2007 I began to realize my Ideas.
</p>
<h2>Future</h2>
<p>
	The following Things I have in mind for future Development:
	<ul>
		<li>More Attributes (Create-Date, last edited, last viewed, who viewed, ....)</li>
		<li>Alternative Design</li>
		<li>Friendlists</li>
		<li>Different levels of publishing (Private/for my Friends/public)</li>
		<li>Some AJAX</li>
		<li>Version History</li>
	</ul>
	If you have an interesting Idea, send me an E-Mail: (notes [at] h-bomb.ch) !
</p>

EOT;
}
###

?>