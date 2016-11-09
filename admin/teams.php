<?php
	require_once('../connections/game.php');
	
	/* GET variables are:
	 * gameId
	 */
	parse_str(http_build_query($_GET));

	echo "<h1>ADD LOCATION</h1>";
	echo "<table><tr>";
	echo "<th>latitude</th>";
	echo "<th>longitude</th>";
	echo "<th>clue</th>";
	echo "<th>description</th>";
	echo "<th>code</th>";
	echo "</tr>";
	echo "<form name='new' method='post' action='http://www.coderace.co.uk/admin/loc_save.php'>";
	echo "<input name='gameId' type='hidden' value='" . $gameId . "'>";
	echo "<tr>";
	echo "<td><input type='text' name='lat' value='" . $loc['latitude'] . "'></td>";
	echo "<td><input type='text' name='long' value='" . $loc['longitude'] . "'></td>";
	echo "<td><input type='text' name='clue' value='" . $loc['clue'] . "'></td>";
	echo "<td><input type='text' name='desc' value='" . $loc['description'] . "'></td>";
	echo "<td><input type='text' name='code' value='" . $loc['code'] . "'></td>";
	echo "<td><input type='submit' value='Save'></td>";
	echo "</tr></form></table>";
	
	// Find the state of the locations
	$query  = "select l.id, l.latitude, l.longitude, l.clue, l.description, l.code ";
	$query .= "from   location l ";
	$query .= "where  l.game_id = " . $gameId;
	
	$result = mysql_query($query) 
		or die("Unable to execute location query: " . mysql_error());
		
	echo "<h1>LOCATIONS: " . mysql_num_rows($result) . "</h1>";
	echo "<table><tr>";
	echo "<th>id</th>";
	echo "<th>latitude</th>";
	echo "<th>longitude</th>";
	echo "<th>clue</th>";
	echo "<th>description</th>";
	echo "<th>code</th>";
	echo "</tr>";

	// Iterate through all the rows in the result set and add a row to the response text
	while ($loc = mysql_fetch_array($result, MYSQL_ASSOC)) {
		echo "<form name='" . $loc['id'] . "' method='post' action='http://www.coderace.co.uk/admin/loc_save.php'>";
		echo "<input name='gameId' type='hidden' value='" . $gameId . "'>";
 		echo "<tr>";
		echo "<td><input type='text' name='id' value='" . $loc['id'] . "'></td>";
		echo "<td><input type='text' name='lat' value='" . $loc['latitude'] . "'></td>";
		echo "<td><input type='text' name='long' value='" . $loc['longitude'] . "'></td>";
		echo "<td><input type='text' name='clue' value='" . $loc['clue'] . "'></td>";
		echo "<td><input type='text' name='desc' value='" . $loc['description'] . "'></td>";
		echo "<td><input type='text' name='code' value='" . $loc['code'] . "'></td>";
		echo "<td><input type='submit' value='Save'></td>";
		echo "</tr></form>";
	}
	echo "</table>";


	mysql_close($link);

?>