<?php
	require_once('../connections/game.php');
	require_once('functions.php');

	$claimed = "false";

	// Extract GET variables
	// gameId, playerId, locationId, code
	extract($_GET);
	
	// Process a claim
	if ($code == "") {
		$message = "No code entered!";
	}
	else {
		// Check the answer
		$query  = "select 'Y' ";
		$query .= "from   location ";
		$query .= "where  id = " . $locationId . " ";
		$query .= "and    upper(code) = upper('" . $code . "')";

	
		$result = mysql_query($query) 
			or die("Unable to execute team query: " . mysql_error());

	if (mysql_num_rows($result) > 0) {
		// Code is correct - check it is free
		$query  = "update location set player_id = " . $playerId . ", claimed = now() ";
		$query .= "where id = " . $locationId . " ";
		$query .= "and game_id = " . $gameId . " ";													
		$query .= "and player_id is null";
		
		logMessage($playerId, "Claim: " . $query);
		$result = mysql_query($query) or die("Unable to update game location: " . mysql_error()); 
		
		if (mysql_affected_rows() == 0) {
			$message="Marker already taken!";
		}
		else {
			$message="Marker claimed!";
			$claimed = "true";

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
	}
	}

	// Start the JSON string
	$responseText="{'message': '" . $message . "',";
	$responseText.="'claimed': " . $claimed . "}";

	echo $responseText;
?>