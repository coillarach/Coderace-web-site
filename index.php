<?php
	session_start();
	require_once('global.php');
	
	// GET var x indicates that the user has just logged out - do not log this visit
	parse_str(http_build_query($_GET));

	if (!isset($_SESSION{user_id}) && !isset($x)) {
		$query  = "insert into visit (host, ip, agent) ";
		$query .= "values ('" . $_SERVER{REMOTE_HOST} . "', ";
		$query .= "        '" . $_SERVER{REMOTE_ADDR} . "', ";
		$query .= "        '" . $_SERVER{HTTP_USER_AGENT} . "')";
		$result = mysql_query($query) or die('Could not log visit: ' . mysql_error());
	}

	include('display.php');
?>