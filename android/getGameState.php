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
	
	// Check device and last activity
	$query  = "select username, device, ifnull(now() - last_activity, 2001) as last_active ";
	$query .= "from player where id = " . $playerId;
	$result = mysql_query($query) or die("Unable to check user activity: " . mysql_error());
	$check  = mysql_fetch_array($result, MYSQL_ASSOC);

	if ($check{device} != '' && $check{device} != $device && intval($check{last_active}) < 2000) {
		$responseText  = "{'message': 'Multiple logins detected for " . $check{username} . "',";
		$responseText .= " 'status': 1}";
		echo $responseText;
		mysql_close($link);
		exit;
	}
	else {
		// Update device id and last activity
		$update = "update player set device = '" . $device . "', last_activity = now() where id = " . $playerId;
		$result = mysql_query($update) or die("Could not update player device: " . mysql_error());
	}

	// If location is supplied, update user's location
	if ($latitude > 0) {
		$query  = "update player ";
		$query .= "set    latitude = "  . $latitude . ", ";
		$query .= "       longitude = " . $longitude . " ";
		$query .= "where  username = '" . $username  . "'";
		$result = mysql_query($query) 
			or die("Unable to update user location: " . mysql_error());
	}

	// Find the state of the teams
	// 4294967295 is white
	$query  = "SELECT t.id, t.name, t.colour, count(d.playerId) as score ";
	$query .= "from team t left join ( ";
	$query .= "select p.id as playerId, p.team_id, l.id as locationId ";
	$query .= "from   player p join location l on p.id = l.player_id ";
	$query .= "where  l.game_id = " . $gameId . ") d ";
	$query .= "on t.id = d.team_id ";
	$query .= "where t.game_id = " . $gameId . " ";
	$query .= "group by t.name ";
	$query .= "union ";
	$query .= "select 1000 as id, 'free' as username, 4294967295 as colour, count(*) as score ";  // username should be name?
	$query .= "from   location ";
	$query .= "where  game_id = " . $gameId . " ";
	$query .= "and    player_id is null ";
	$query .= "union ";
	$query .= "select 2000 as id, 'total' as username, 4294967295 as colour, count(*) as score ";  // username should be name?
	$query .= "from   location ";
	$query .= "where  game_id = " . $gameId . " ";
	
	$result = mysql_query($query) 
		or die("Unable to execute team query: " . mysql_error());

	// Start the JSON string
	// $message is set by claim_ANDROID.php
	$responseText  = "{'message': '" . $message . "',";
	$responseText .= "'status': 0, ";
	$responseText .= "'teamNumber': ";
	$responseText .= mysql_num_rows($result);
	$responseText .= ", 'teams': [";

	$loopCount=0;
	// Iterate through all teams in the game and find their scores
	while ($team = mysql_fetch_array($result, MYSQL_ASSOC)) {
		if ($loopCount>0) {
			$responseText .= ",";
		}
		else {
			$loopCount++;
		}
		$responseText .= "{'id':".$team{id}.",";
		$responseText .= " 'name':'".str_replace("'","\'",$team{name})."',";
		$responseText .= " 'colour':".$team{colour}.",";
		$responseText .= " 'score':".$team{score}."}";
		$locationNumber = $team{score};
	}
	$responseText .= "], 'locationNumber': ";
	$responseText .= $locationNumber;

	// Find the state of the locations
	$query  = "select l.id, l.latitude, l.longitude, l.clue, l.visible, ifnull(t.id,0) as 'tid' ";
	$query .= "from   location l left join player p on p.id = l.player_id ";
	$query .= "       left join team t on t.id = p.team_id ";
	$query .= "where  l.game_id = " . $gameId;
	
	$result = mysql_query($query) 
		or die("Unable to execute location query: " . mysql_error());

	$responseText .= ", 'locations': [";

	$loopCount=0;
	// Iterate through all the rows in the result set and add a row to the response text
	while ($loc = mysql_fetch_array($result, MYSQL_ASSOC)) {
		if ($loopCount>0) {
			$responseText .= ",";
		}
		else {
			$loopCount++;
		}
		$responseText .= "{'id':" .$loc{id}.",";
		$responseText .= " 'latitude':" .$loc{latitude}.",";
		$responseText .= " 'longitude':".$loc{longitude}.",";
		$responseText .= " 'clue':'"     .str_replace("'","\'",$loc{clue})."',";
		$responseText .= " 'visible':'".$loc{visible}."',";
		$responseText .= " 'team':".$loc{tid}."}";
	}

	// Find the location of the players
	$query  = "select p.id, t.id as tid, p.username, coalesce(p.latitude,0) as latz, coalesce(p.longitude,0) as longz ";
	$query .= "from team t join player p on p.team_id = t.id ";
	$query .= "where t.game_id = " . $gameId;
	
	$result = mysql_query($query) 
		or die("Unable to execute user query: " . mysql_error());

	$responseText.="], 'playerNumber': ";
	$responseText.=mysql_num_rows($result);
	$responseText .= ", 'players': [";

	$loopCount=0;
	// Iterate through all the rows in the result set and add a row to the response text
	while ($player = mysql_fetch_array($result, MYSQL_ASSOC)) {
		if ($loopCount>0) {
			$responseText .= ",";
		}
		else {
			$loopCount++;
		}
		$responseText .= "{'id':" . $player{id}.",";
		$responseText .= " 'team':" .$player{tid}.",";
		$responseText .= " 'username':'" .$player{username}."',";
		$responseText .= " 'latitude':" .$player{latz}.",";
		$responseText .= " 'longitude':".$player{longz}."}";
	}

	 

	// Terminate JSON string
	$responseText .= "]}";

	echo $responseText;

	mysql_close($link);

?>
