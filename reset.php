<?php

/* Remove all claim details and reveal the starting locations
 */

	session_start();
	require_once('global.php');

	parse_str(http_build_query($_POST));

	$CONTENT = get_type('game');
	$CONTENT->populate($id);
	
	$MESSAGE = $CONTENT->reset();

	push(0, 'game', 1, 'list');

	header('Location: display.php');
?>