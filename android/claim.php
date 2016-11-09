<?php

// Statuses:
// 0 = claimed
// 1 = no code entered
// 2 = marker already taken
// 3 = incorrect code

	require_once('../connections/game.php');
	require_once('functions.php');
	
	$claimed = "false";
	$status = 1000;

	// Extract GET variables
	// gameId, playerId, locationId, code
	extract($_GET);
	
	// Process a claim
	if ($code == "") {
		$message = "No code entered!";
                $status = 1;
	}
	else {
		// Check the answer
		$query  = "select 'Y' ";
		$query .= "from   location ";
		$query .= "where  id = " . $locationId . " ";
		$query .= "and    upper(code) = upper('" . $code . "')";

	
		$result = mysql_query($query) 
			or die("Unable to execute claim check query: " . mysql_error());

	if (mysql_num_rows($result) > 0) {
		// Code is correct - check it is free
		$query  = "update location set player_id = " . $playerId . ", claimed = now() ";		// Changed teamId to playerId
		$query .= "where id = " . $locationId . " ";
		$query .= "and game_id = " . $gameId . " ";							// Changed gameInstanceId to gameId
	        $query .= "and player_id is null";
		$result = mysql_query($query) or die("Unable to update game location: " . mysql_error()); 
		
		logMessage($playerId, "Claim: " . $query);
		
		if (mysql_affected_rows() == 0) {
			$message="Marker already taken!";
                        $status = 2;
		}
		else {
			$message="Marker claimed!";
			$claimed = "true";
                        $status = 0;

			// Reveal two more markers if available
			$query  = "select id, round(rand()) as ord_col ";
			$query .= "from   location ";
			$query .= "where  visible = 0 ";
			$query .= "and    game_id = " . $gameId . " ";
			$query .= "order by 2 limit 2";
			$result = mysql_query($query) or die("Unable to execute marker query: " . mysql_error());

			while ($loc = mysql_fetch_assoc($result)) {
				$query2  = "update location set visible = 1 ";
				$query2 .= "where  id = " . $loc{id} . " ";
				$query2 .= "and    game_id = " . $gameId;
				$result2 = mysql_query($query2) or die("Unable to update markers: " . mysql_error());
			}
		}
	}
	else {
		// Code is incorrect
		$message="Wrong code!";
                $status = 3;
	}
	}

	// Save event record
	$query  = "insert into event (game_id, player_id, location_id, event_type, event_message, event_detail) ";
	$query .= "			  values (" . $gameId . ", " . $playerId . ", " . $locationId . ", ";
	if ($claimed == "true")
		$query .= "'claim_success', ";
	else
		$query .= "'claim_fail', ";
	$query .= "'" . $message . "', '" . $code . "')";
	$result = mysql_query($query) or die("Could not insert event record. " . mysql_error());
	
	// Start the JSON string
	$responseText ="{'status':  "   . $status     . ",";
	$responseText.="'message': '"   . $message    . "',";
	$responseText.="'locationId': " . $locationId . ",";
	$responseText.="'claimed': "    . $claimed    . "}";

	echo $responseText;
?>
