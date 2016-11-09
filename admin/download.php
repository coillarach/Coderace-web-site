<?php
	require_once('../connections/game.php');
	
	/* GET variables are:
	 * playerId
	 * gameId
	 * username
	 * latitude
	 * longitude
	 * device
	 */
	parse_str(http_build_query($_GET));
	
	// Find the state of the teams
	// 4294967295 is white
	$query  = "SELECT t.id, t.name ";
	$query .= "from team t ";
	$query .= "where t.game_id = " . $gameId;
	
	$result = mysql_query($query) 
		or die("Unable to execute team query: " . mysql_error());
	
	echo "TEAMS: " . mysql_num_rows($result) . "<br>";
	echo "id,name<br>";
	// Iterate through all the rows in the result set
	while ($t = mysql_fetch_array($result, MYSQL_ASSOC)) {
		echo $t['id'] . "," . $t['name'] . "<br>";
	}
	
	// Find the state of the locations
	$query  = "select l.id, l.latitude, l.longitude, l.clue, l.description, l.code ";
	$query .= "from   location l ";
	$query .= "where  l.game_id = " . $gameId;
	
	$result = mysql_query($query) 
		or die("Unable to execute location query: " . mysql_error());
		
	echo "<br>LOCATIONS: " . mysql_num_rows($result) . "<br>";
	echo "id,latitude,longitude,clue,description,code<br>";

	// Iterate through all the rows in the result set and add a row to the response text
	while ($loc = mysql_fetch_array($result, MYSQL_ASSOC)) {
		echo $loc['id'] . ",";
		echo $loc['latitude'] . ",";
		echo $loc['longitude'] . ",";
		echo $loc['clue'] . ",";
		echo $loc['description'] . ",";
		echo $loc['code'] . "<br>";
	}


	mysql_close($link);

?>