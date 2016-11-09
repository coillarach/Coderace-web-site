<?php
	require_once('../connections/game.php');
	
	/* POST variables are:
	 * id
	 * username
	 * password
	 * team_id
	 */
//	parse_str(http_build_query($_GET));
	extract($_POST);

	echo "id: " . $id . "<br>";
	echo "un: " . $username . "<br>";
	echo "pw: " . $password . "<br>";
	echo "team_id: " . $team_id . "<br>";
	
	if ($id) {
		$query  = "update player set ";
		$query .= "username='" . $username . "', ";
		$query .= "password='" . $password . "', ";
		$query .= "team_id=" . $team_id. " ";
		$query .= "where  id = " . $id;
	}
	else {
		$query  = "insert into player(team_id, username, password) ";
		$query .= "values (" . $team_id . ", '";
		$query .= $username . "', '";
		$query .= $password . "')";
	}
	
	echo $query;
	
	$result = mysql_query($query) or mysql_error();
		

	mysql_close($link);

?>