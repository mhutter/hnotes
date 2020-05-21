<?php
/************************************/
/* Beispielmodul für gPROFILE.net	*/
/* Autor: Manuel Hutter				*/
/************************************/

unset($nlogin, $npass, $_SESSION['nlogin'], $_SESSION['npass'], $uid, $logged_as);

setcookie('nlogin',	false,	1);
setcookie('npass',	false,	1);

$logged_in = false;

redirect('login');

?>