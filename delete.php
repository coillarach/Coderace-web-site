<?php

/* Deletes a content item
 * One file handles all type of content using variable values passed in from the edit form
 */

	session_start();
	require_once('global.php');

	parse_str(http_build_query($_POST));

	$CONTENT = get_type($tableName);
	$CONTENT->populate($id);
	
	$MESSAGE = $CONTENT->delete();

	if ($list_flag == 0) {
		$mode='list';
		push(0, $CONTENT->contentType, $CONTENT->authorId, $mode);
	}
	else {
		$mode='display';
		if ($MESSAGE == 0) {
			$CONTENT = get_type($successItemType);
			$CONTENT->populate($successItemId);
		}
		push($successItemId, $successItemType, $CONTENT->authorId, $mode);
	}

	header('Location: display.php');
?>