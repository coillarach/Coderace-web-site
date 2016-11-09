<?php

	error_reporting(E_ALL ^ E_NOTICE);

	// Set access details
	$hs="localhost";
	$un="o110648_cr";
	$pw="C0d3rac3";
	$db="o110648_coderace_games";

	// Connect to the database using the root user which by default has no password
	$link = mysql_connect($hs,$un,$pw)
		or die("Unable to connect to MySQL: ".mysql_error());

	// Select the coderace database
	mysql_select_db($db)
		or die("Unable to select database: ".mysql_error());

?>
