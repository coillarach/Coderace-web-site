<?php

// Statuses:
// 0 = Logged in
// 4 = Incorrect username
// 5 = Incorrect password
// 6 = Multiple logins
// 7 = Game not started
// 8 = Username or password missing

require_once('../connections/game.php');
require_once('functions.php');

// Initialise return variables
$message = "";
$status = 1000;

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
        $query .= "       p.password, ";
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
	$query .= "where p.username = '" . $user . "' ";
   	$result = mysql_query($query) or die('Invalid query: ' . mysql_error() . "\n");
	
	// If no result is returned, the username is incorrect
	if (mysql_num_rows($result) == 0) {
		$message = "Username not recognised";
                $status = 4;
	}
	else {
		$game = mysql_fetch_array($result, MYSQL_ASSOC) or die('Could not fetch game details: ' . mysql_error() . "\n");
//		logMessage($game{player_id}, "Login: " . $query);

                if ($game{password} != $pass) {
                        $message = "Password not recognised";
                        $status = 5;
			$game = array();
                }		
		elseif ($game{device} != '' && $game{device} != $device && intval($game{last_active}) < 2000) {
			$message = "Multiple logins detected for " . $user;
                        $status = 6;
			$game = array();
		}
//		elseif ($game{seconds_to_go} > 0) {
//			$message = "Game starts in " . $game{time_to_go};
//                      $status = 7;
//			$game = array();
//		}
		else {
			// Update device id and last activity
			$update = "update player set device = '" . $device . "', last_activity = now() where id = " . $game{player_id};
			$subresult = mysql_query($update) or die("Could not update player device: " . mysql_error());
			
			logMessage($game{player_id}, "Login. Username: " . $user . "; Password: " . $pass . "; Player id: " . $game{player_id} . "; Device: " . $device);
			$message = "Connected!";
                        $status = 0;
		}
	}
} 
else {
	$message = "Please provide username and password";
        $status = 8;
}
if ($game{player_id}) {
	$responseText  = "{'status':"   . $status .",";
	$responseText .= "'message':'"  . $message ."',";
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
	$responseText = "{'status':" . $status . ",'message':'" . $message . "'}";

print $responseText;
