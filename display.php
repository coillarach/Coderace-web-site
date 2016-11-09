<?php

/* Displays content in the main DIV
 * Ensures that the $mode variable is set to display, edit or list
 * $mode is used in the template file to produce either a readable display or an editable form
 */

	session_start();
	require_once('global.php');

	if (isset($_SESSION{user_id}) && isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {	// last request was more than 30 minutes ago

		// Record logout time
		$query  = "update login set logout_date = current_timestamp ";
		$query .= "where session_id = '" . session_id() . "'";
		$result = mysql_query($query) or die('Could not record logout ' . mysql_error());

		$_SESSION = array();			// re-initialise the array to remove references to variables
		session_destroy();   			// destroy session data in storage
		session_unset();     			// unset $_SESSION variable for the runtime
		$MESSAGE = 8;
		$CONTENT_ID = 1;
		$CONTENT_TYPE='document';
		$CONTENT_AUTHOR = 0;
		$mode = 'display';
	}
	else {
		$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp	
		$MESSAGE = $_GET{o};
		
//print_r($_GET);
//print_r($_POST);
//print_r($_SESSION);	

		if (isset($_SESSION{id})) {					// If SESSION vars are set, we have been redirected from an update script
			$CONTENT_ID = $_SESSION{id};
			$CONTENT_TYPE = $_SESSION{contentType};
			$CONTENT_AUTHOR = $_SESSION{authorId};
			$mode = $_SESSION{mode};
			unset($_SESSION{id});
			unset($_SESSION{contentType});
			unset($_SESSION{authorId});
			unset($_SESSION{mode});
		}
		else if (isset($_GET{i})) {					// If GET vars are set, this is an embedded content link
			$CONTENT_TYPE	= $_GET{t};
			$mode			= $_GET{m};
			if ($mode == 'edit') {
				if ($CONTENT_TYPE == 'user' && isset($_SESSION{user_id}))
					$CONTENT_ID = $_SESSION{user_id};
				else
					$CONTENT_ID = 0;
				$CONTENT_AUTHOR	= $_SESSION{user_id};
			}
			else {
				$CONTENT_ID = $_GET{i};
				$CONTENT_AUTHOR = $_GET{a};
			}
		}
		else {
			// id = 0 indicates a new item of the relevant type
			$CONTENT_ID     = (isset($_POST{id})          ? $_POST{id}          : 1);
			$CONTENT_TYPE   = (isset($_POST{contentType}) ? $_POST{contentType} : 'document');
			$CONTENT_AUTHOR = (isset($_POST{author_id})   ? $_POST{author_id}   : 0);
			$mode           = (isset($_POST{mode})        ? $_POST{mode}        : 'display');
		}
	}

	if ($mode == "list") {
		$CONTENT_ID = 0;
		$CONTENT = new ContentList($CONTENT_TYPE);
		$CONTENT->populate();
	}
	else { 
	
		$CONTENT = get_type($CONTENT_TYPE);
		if ($CONTENT_ID > 0)
			$CONTENT->populate($CONTENT_ID);
		else
			$CONTENT->id = $CONTENT_ID;				// Takes account of password reset where id = -1
	}

//print_r($CONTENT);
	include('templates/' . $TEMPLATE . '.php'); 

	mysql_close($DB_LINK);
?>