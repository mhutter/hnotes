<?php

$logged_in	= false;

if( $_COOKIE['nlogin'] && $_COOKIE['npass'] )
{
	$_SESSION['nlogin']	= $_COOKIE['nlogin'];
	$_SESSION['npass']	= $_COOKIE['npass'];
}

if($_SESSION['nlogin'] && $_SESSION['npass'])
{
	$nlogin	= $_SESSION['nlogin'];
	$npass	= $_SESSION['npass'];
}
elseif($_POST['nlogin'] && $_POST['npass'])
{
	$nlogin	= $_POST['nlogin'];
	$npass	= md5($_POST['npass']);
}
else
{
	$nlogin = false;
	$npass	= false;
}

if($nlogin && $npass)
{
	$query = "SELECT * FROM notes_users WHERE u_login='{$nlogin}' LIMIT 0,1";
	$result = mysql_query($query);
	if($result)
	{
		$row = mysql_fetch_array($result);
		
		if($row['u_pwd'] == $npass)
		{
			$logged_in			= true;
			$logged_as			= $row['u_login'];
			$uid				= $row['u_id'];
			$_SESSION['nlogin']	= $row['u_login'];
			$_SESSION['npass']	= $row['u_pwd'];
			if( $_POST['nstay'] == 'on' )
			{
				setcookie('nlogin',	$row['u_login'],	time()+60*60*24*365, '/', '', false, true);
				setcookie('npass',	$row['u_pwd'],		time()+60*60*24*365, '/', '', false, true);
			}
		}
		
	}
	else
	{
		$nlogin = '';
	}
}

?>