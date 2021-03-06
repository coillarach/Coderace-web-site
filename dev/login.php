<?php

require_once('../connections/game.php');
require_once('functions.php');

// Initialise return variables
$message = "";

/* GET variables are:
 * user = username
 * pass = password
 * device = device id
 */
parse_str(http_build_query($_GET));

// If a username and password is supplied, check against the database
// Return the game id if successful
// Otherwise return error messages as necessary

if ($user != "" && $pass != "") {
	// Check the email and password in the database
	$query  = "select p.id as player_id, ";
	$query .= "       ifnull(p.device, '') as device,  ";
	$query .= "       ifnull(now() - last_activity, 2001) as last_active, ";
	$query .= "       g.id as game_id, ";
	$query .= "       g.name, ";
	$query .= "       g.latitude, ";
	$query .= "       g.longitude, ";
	$query .= "       g.zoom, ";
	$query .= "		  unix_timestamp(now()) as now, ";
	$query .= "       unix_timestamp(g.start) as start, ";
	$query .= "       unix_timestamp(g.end) as end, ";
	$query .= "		  timestampdiff(SECOND, now(), g.start) as seconds_to_go, ";
	$query .= "		  time_format(timediff(g.start, now()), '%H hours %i minutes %s seconds') as time_to_go, ";
	$query .= "       t.id as team_id ";
	$query .= "from team t join player p on t.id = p.team_id ";
	$query .= "       join game g on t.game_id = g.id ";
	$query .= "where p.username = '" . $user . "' and p.password = '" . $pass . "' ";
   	$result = mysql_query($query) or die('Invalid query: ' . mysql_error() . "\n");
	
	// If no result is returned, the details are incorrect
	if (mysql_num_rows($result) == 0) {
		$message = "User details not recognised";
	}
	else {
		$game = mysql_fetch_array($result, MYSQL_ASSOC) or die('Could not fetch game details: ' . mysql_error() . "\n");
		logMessage($game{player_id}, "Login: " . $query);
		
		if ($game{device} != '' && $game{device} != $device && intval($game{last_active}) < 2000) {
			$message = "Multiple logins detected for " . $user;
			$game = array();
		}
		elseif ($game{seconds_to_go} > 0) {
			$message = "Game starts in " . $game{time_to_go};
			$game = array();
		}
		else {
			// Update device id and last activity
			$update = "update player set device = '" . $device . "', last_activity = now() where id = " . $game{player_id};
			$subresult = mysql_query($update) or die("Could not update player device: " . mysql_error());
			
			$message = "Connected!";
		}
	}
} 
else {
	$message = "Please provide username and password";
}
if ($game{player_id}) {
	$responseText  = "{'message':'" . $message ."',";
	$responseText .= "'playerId':"  . $game{player_id} .",";
	$responseText .= "'gameId':"    . $game{game_id} .",";
	$responseText .= "'gameName':'" . $game{name} ."',";
	$responseText .= "'longitude':" . $game{longitude} .",";
	$responseText .= "'latitude':"  . $game{latitude} .",";
	$responseText .= "'zoom':"      . $game{zoom} .",";
	$responseText .= "'serverTime':". $game{now} . ",";
	$responseText .= "'gameStart':" . $game{start} .",";
	$responseText .= "'gameEnd':"   . $game{end} .",";
	$responseText .= "'teamId':"    . $game{team_id} ."}";
}
else
	$responseText = "{'message':'" . $message . "'}";

print $responseText;
