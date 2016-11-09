<?php

	require_once('../connections/game.php');
	require_once('functions.php');

	// Initialise return variables
	$message = "";

	/* GET variables are:
	* playerId
	*/
	parse_str(http_build_query($_GET));

	// Check the email and password in the database
	$query  = "update player set device = null, ";
	$query .= "					 last_activity = null, ";
	$query .= "					 latitude = 90, ";
	$query .= "					 longitude = 0 ";
	$query .= "where id = " . $playerId;
	
	logMessage($playerId, "Logout: " . $query);

   	$result = mysql_query($query) or die('Logout error: ' . mysql_error() . "\n");


$responseText  = "{'message':'Disconnected'}";

print $responseText;