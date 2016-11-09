<?php

	session_start();
	require_once('global.php');

	$query1  = "select id from visit where visit_date = (select max(visit_date) from visit where ip = '" . $_SERVER{REMOTE_ADDR} . "')";
	$result1 = mysql_query($query1) or die("Can't get most recent visit: " . mysql_error());
	$row1 = mysql_fetch_array($result1, MYSQL_ASSOC);
	
	$query2  = "update visit set download = now() where id = " . $row1{id};
	$result2 = mysql_query($query2) or die("Could not log visit: " . mysql_error());
	
	mysql_close($DB_LINK);
	
	header('Location: android/Coderace.apk');

?>