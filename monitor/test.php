<!DOCTYPE html>
<?php
	require_once('../connections/game.php');
	
	$query  = "select date_format(e.event_date, '%H:%i:%s') as etime, e.player_id, p.username, l.id, e.event_type, e.event_message, e.event_detail ";
	$query .= "from   event e join player p on p.id = e.player_id ";
	$query .= "       left join location l on l.id = e.location_id ";
	$query .= "where  e.game_id = 7";
	$result = mysql_query($query) or die("Could not get events: " . mysql_error());

	echo "<html><head><title>Coderace</title><body>";
	echo "<table>";
	echo "<tr><td>time</td>";
	echo "    <td>player</td>";
	echo "    playerId</td>";
	echo "    location</td>";
	echo "    type</td>";
	echo "    message</td>";
	echo "    detail</td>";
	echo "</tr>";

	while ($event = mysql_fetch_array($result, MYSQL_ASSOC)) {
		echo "<tr><td>" . $event{etime}."</td>";
		echo "    <td>" .$event{username}."</td>";
		echo "    <td>" .$event{player_id}."</td>";
		echo "    <td>" .$event{id}."</td>";
		echo "    <td>" .$event{event_type}."</td>";
		echo "    <td>" .$event{event_message}."</td>";
		echo "    <td>" .str_replace("'","\'",$event{event_detail})."</td>";
	}

	echo "</table>";
  	echo "</body></html";
?>