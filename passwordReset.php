<?php

// resets password

	session_start();
	require_once('global.php');

	parse_str(http_build_query($_POST));

	$CONTENT = new User();
	$MESSAGE = $CONTENT->passwordReset($email);
	
	header('Location: ' . absolute_url('index.php?o=' . $MESSAGE));
?>