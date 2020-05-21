<?php
if($sprache == 'en') {
	$c["bar"] = 'Logged in as '.$logged_as.' - <a href="/settings">Settings</a> - <a href="/logout">Logout</a>';
} else {
	$c["bar"] = 'Eingeloggt als '.$logged_as.' - <a href="/settings">Einstellungen</a> - <a href="/logout">Abmelden</a>';
}

?>