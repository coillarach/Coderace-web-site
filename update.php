<?php

/* Updates the status of a request
 */

	session_start();
	require_once('global.php');

	parse_str(http_build_query($_POST));

	$request = new Request();
	$request->populate($id);
	
	if ($action == 'accept')
		$request->accept();
	else
		$request->reject();
		
	$mode='display';
	$CONTENT = get_type('team');
	$CONTENT->populate($request->teamId);

	push($request->teamId, 'team', $CONTENT->authorId, 'display');
	header('Location: display.php');
	
?>