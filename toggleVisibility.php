<?php

// Show/hide a content item

	session_start();
	require_once('global.php');

	parse_str(http_build_query($_POST));

	$CONTENT = get_type($tableName);
	$CONTENT->populate($id);
	
	$MESSAGE = $CONTENT->toggleVisible();

	if ($list_flag == 0) {
		$mode='list';
		push(0, $CONTENT->contentType, $CONTENT->authorId, $mode);
	}
	else {
		$mode='display';
		push($CONTENT->id, $CONTENT->contentType, $CONTENT->authorId, $mode);
	}

	header('Location: display.php');
?>