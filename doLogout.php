<?php
	session_start();
	require_once('global.php');
	
	// Record logout time
	$query  = "update login set logout_date = current_timestamp ";
	$query .= "where session_id = '" . session_id() . "'";
	$result = mysql_query($query) or die('Could not record logout ' . mysql_error());

	$_SESSION = array();
	session_destroy();
//	setcookie('PHPSESSID','',time()-3600,'/','',0,0);  Final parameter added in PHP v. 5.2.0
	setcookie('PHPSESSID','',time()-3600,'/','',0);
	header('Location: ' . absolute_url('index.php?x=y'));
?>

