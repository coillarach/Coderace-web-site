<?php
	require_once('../connections/game.php');
	
	/* POST variables are:
	 * gameId
	 * id
	 * lat
	 * long
	 * clue
	 * desc
	 * code
	 */
//	parse_str(http_build_query($_GET));
	extract($_POST);

	echo "gameId: " . $gameId . "<br>";
	echo "id: " . $id . "<br>";
	echo "lat: " . $lat . "<br>";
	echo "long: " . $long . "<br>";
	echo "clue: " . $clue . "<br>";
	echo "desc: " . $desc . "<br>";
	echo "code: " . $code . "<br>";
	
	if ($id) {
		$query  = "update location set ";
		$query .= "latitude=" . $lat . ", ";
		$query .= "longitude=" . $long . ", ";
		$query .= "clue='" . $clue . "', ";
		$query .= "description='" . $desc . "', ";
		$query .= "code='" . $code . "' ";
		$query .= "where  id = " . $id;
	}
	else {
		$query  = "insert into location (game_id, latitude, longitude, clue, description, code) ";
		$query .= "values (" . $gameId . ", ";
		$query .= $lat . ", ";
		$query .= $long . ", '";
		$query .= $clue . "', '";
		$query .= $desc . "', '";
		$query .= $code . "')";
	}
	
	echo $query;
	
	$result = mysql_query($query) or mysql_error();
		

	mysql_close($link);

?>