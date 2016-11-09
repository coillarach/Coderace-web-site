<?php
	require_once('../connections/game.php');
	


	echo "<h1>ADD PLAYER</h1>";
	echo "<table><tr>";
	echo "<th>username</th>";
	echo "<th>password</th>";
	echo "<th>team_id</th>";
	echo "</tr>";
	echo "<form name='new' method='post' action='http://www.coderace.co.uk/admin/player_save.php'>";
	echo "<tr>";
	echo "<td><input type='text' name='username'></td>";
	echo "<td><input type='text' name='password'></td>";
	echo "<td><input type='text' name='team_id'></td>";
	echo "<td><input type='submit' value='Save'></td>";
	echo "</tr></form></table>";
	
	// Find the state of the locations
	$query  = "select id, username, password, team_id ";
	$query .= "from   player ";
	
	$result = mysql_query($query) 
		or die("Unable to execute player query: " . mysql_error());
		
	echo "<h1>PLAYERS: " . mysql_num_rows($result) . "</h1>";
	echo "<table><tr>";
	echo "<th>id</th>";
	echo "<th>username</th>";
	echo "<th>password</th>";
	echo "<th>team_id</th>";
	echo "</tr>";

	// Iterate through all the rows in the result set and add a row to the response text
	while ($player = mysql_fetch_array($result, MYSQL_ASSOC)) {
		echo "<form name='" . $player['id'] . "' method='post' action='http://www.coderace.co.uk/admin/player_save.php'>";
 		echo "<tr>";
		echo "<td><input type='text' name='id' value='" . $player['id'] . "'></td>";
		echo "<td><input type='text' name='username' value='" . $player['username'] . "'></td>";
		echo "<td><input type='text' name='password' value='" . $player['password'] . "'></td>";
		echo "<td><input type='text' name='team_id' value='" . $player['team_id'] . "'></td>";
		echo "<td><input type='submit' value='Save'></td>";
		echo "</tr></form>";
	}
	echo "</table>";


	mysql_close($link);

?>