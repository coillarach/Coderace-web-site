<?php
	require_once('../connections/game.php');
	
	// Extract POST variables
	$gameId = $_GET{gameId};

	// Find game status
	$query  = "select g.name, ";
	$query .= "		  timestampdiff(SECOND, g.end, now()) as seconds_since_end, ";
	$query .= "		  timestampdiff(SECOND, now(), g.start) as seconds_before_start, ";
	$query .= "		  time_format(timediff(g.start, now()), '%H') as hours_to_go, ";
	$query .= "		  time_format(timediff(g.start, now()), '%i') as minutes_to_go, ";
	$query .= "		  time_format(timediff(g.start, now()), '%s') as seconds_to_go, ";
	$query .= "		  time_format(timediff(g.start, now()), '%H hours %i minutes %s seconds') as time_to_go ";
	$query .= "from game g ";
	$query .= "where g.id = " . $gameId . " ";
   	$result = mysql_query($query) or die('Invalid query: ' . mysql_error() . "\n");
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	
	if ($row{seconds_since_end} > 0)
		$gameStatus = 'over';
	elseif ($row{seconds_before_start} > 0)
		$gameStatus = 'waiting';
	else
		$gameStatus = 'current';
	$gameTime = $row{time_to_go};
	$remaining = -1 * $row{seconds_since_end};
	
	// Find the state of the teams
	$query  = "SELECT t.id, t.name, t.icon, count(d.playerId) as score ";
	$query .= "from team t left join ( ";
	$query .= "select p.id as playerId, p.team_id, l.id as locationId ";
	$query .= "from   player p join location l on p.id = l.player_id ";
	$query .= "where  l.game_id = " . $gameId . ") d ";
	$query .= "on t.id = d.team_id ";
	$query .= "where t.game_id = " . $gameId . " ";
	$query .= "group by t.name ";
	$query .= "union ";
	$query .= "SELECT 0 as id, 'free' as name, 'free.svg' as icon, count(*) as score ";
	$query .= "from   location ";
	$query .= "where  game_id = " . $gameId . " ";
	$query .= "and    player_id is null ";
	$query .= "union ";
	$query .= "select 0 as id, 'total' as name, null as icon, count(*) as score ";
	$query .= "from   location ";
	$query .= "where  game_id = " . $gameId . " ";

	$result = mysql_query($query) 
		or die("Unable to execute team query: " . mysql_error());

	// Start the JSON string
	// $message is set by claimMarker.php
	$responseText="{gameId: '" . $gameId . "',";
	$responseText.="gameStatus: '" . $gameStatus . "',";
	$responseText.="gameTime: '" . $gameTime . "',";
	$responseText.="remaining: " . $remaining . ",";
	$responseText.="message: '" . $message . "',";
	$responseText.="teams: [";

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
		$responseText .= " 'icon':'".$team{icon}."',";
		$responseText .= " 'score':".$team{score}."}";
	}

	// Find the location of the players
	$query  = "select t.name, t.icon, p.team_id, p.id, p.username, coalesce(p.latitude,0) as latz, coalesce(p.longitude,0) as longz ";
	$query .= "from team t join player p on p.team_id = t.id ";
	$query .= "where t.game_id = " . $gameId;
	
	$result = mysql_query($query) 
		or die("Unable to execute user query: " . mysql_error());

	$responseText.="], playerNumber:";
	$responseText.=mysql_num_rows($result);
	$responseText .= ",players: [";

	$loopCount=0;
	
	// Iterate through all the rows in the result set and add a row to the response text
	while ($player = mysql_fetch_array($result, MYSQL_ASSOC)) {
		if ($loopCount>0) {
			$responseText .= ",";
		}
		else {
			$loopCount++;
		}
		$responseText .= "{'team':'"     .str_replace("'","\'",$player{name})     ."',";
		$responseText .= " 'teamId':"    .$player{team_id}  .",";
		$responseText .= " 'icon':'"     .$player{icon}     ."',";
		$responseText .= " 'id':"    	 .$player{id}       .",";
		$responseText .= " 'username':'" .$player{username} ."',";
		$responseText .= " 'latitude':"  .$player{latz}     .",";
		$responseText .= " 'longitude':" .$player{longz}    ."}"; 
	}

	// Find the state of the locations
	$query  = "select l.id, l.latitude, l.longitude, l.clue, l.visible, t.name, t.icon, ";
	$query .= "       p.username ";
	$query .= "from   location l left join player p on p.id = l.player_id ";
	$query .= "       left join team t on t.id = p.team_id ";
	$query .= "where  l.game_id = " . $gameId;

	$result = mysql_query($query) 
		or die("Unable to execute location query: " . mysql_error());

	$responseText .= "], locations: [";

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
		$responseText .= " 'team':'".$loc{name}."',";
		$responseText .= " 'player':'".$loc{username}."',";
		$responseText .= " 'icon':'".$loc{icon}."'}";
	}
	
	$responseText .= "], events: [";
	
	$query  = "select date_format(e.event_date, '%H:%i:%s') as etime, e.player_id, p.username, l.id, e.event_type, e.event_message, e.event_detail ";
	$query .= "from   event e join player p on p.id = e.player_id ";
	$query .= "       left join location l on l.id = e.location_id ";
	$query .= "where  e.game_id = " . $gameId . " ";
	$query .= "and    timestampdiff(SECOND, event_date, now()) < 600 ";
	$query .= "order by event_date desc limit 8";
	$result = mysql_query($query) or die("Could not get events: " . mysql_error());
//echo "XXXX " + mysql_num_rows($result) + " XXXX";
	$loopCount=0;
	// Iterate through all the rows in the result set and add a row to the response text
	while ($event = mysql_fetch_array($result, MYSQL_ASSOC)) {
		if ($loopCount>0) {
			$responseText .= ",";
		}
		else {
			$loopCount++;
		}
		$responseText .= "{'time':'" .$event{etime}."',";
		$responseText .= " 'player':'" .$event{username}."',";
		$responseText .= " 'playerId':".$event{player_id}.",";
		$responseText .= " 'location':".$event{id}.",";
		$responseText .= " 'type':'".$event{event_type}."',";
		$responseText .= " 'message':'".$event{event_message}."',";
//		$responseText .= " 'detail':'".$event{event_detail}."'}";
		$responseText .= " 'detail':'".str_replace("'","\'",$event{event_detail})."'}";
	}

	// Terminate JSON string
	$responseText .= "]}\n";

	echo $responseText;

	mysql_close($link);

?>