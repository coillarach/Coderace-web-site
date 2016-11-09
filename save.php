<?php

/* Saves a content item after editing
 * One file handles all type of content using variable values passed in from the edit form
 */

	session_start();
	require_once('global.php');

	$CONTENT_TYPE = $_POST{tableName};
	$CONTENT_ID = $_POST{id};
	$error = false;

	// Remove non-database fields from the list ###################
	unset($_POST{check_password});
	unset($_POST{agree}); // Agreement is checked by javascript validation
		
	// Get the information about this table from the database
	$datatype = array();
	$query = "select column_name, data_type from information_schema.columns where table_schema = '" . $DB_NAME . "' and table_name = '" . $CONTENT_TYPE . "'";
	$result = mysql_query($query) or die($_SERVER['PHP_SELF'] . ' Invalid query: ' . mysql_error() . "\n");
	
	while($row = mysql_fetch_array($result, MYSQL_ASSOC))
		$datatype{$row{column_name}} = $row{data_type};

		if ($CONTENT_ID > 0) {
		$query = "update " . $CONTENT_TYPE . " set ";
		$first = true;
		
		foreach ($_POST as $column => $value) {
			if ($column == "tableName" || $column == "id" || $column == "author_id")
				continue;
				
			if ($column == 'password') {
				if ($value == '')
					continue;
				else
					$value = sha1($value);
			}
				
			if ($first)
				$first=false;
			else
				$query .= ", ";
				
			$query .= $column . " = ";
			if ($datatype{$column} == "text" || $datatype{$column} == "varchar" || $datatype{$column} == "datetime") {
				if (substr_count($value, "\\'") > 0)
					$value = str_replace("\\'","''",$value);
				else
					$value = str_replace("'","''",$value);
				$query .= "'" . $value . "' ";
			}
			else
				$query .= $value;
		}
		
		$query .= " where id = " . $_POST{id};
	}
	else {
		$query = "insert into " . $CONTENT_TYPE . " (";
		$colNames = "";
		$colValues = "";
		
		$first = true;

		// Assumes that form fields have the same names as table columns
		foreach($_POST as $column => $value) {
			// Skip content type and id values
			if ($column == "tableName" || $column == "id")
				continue;
				
			if ($column == "image")
				$parameter = $value;			// Save for later in case this is a new user
			
			if ($first)
				$first = false;
			else {
				$colNames .= ", ";
				$colValues .= ", ";
			}
			
			$colNames .= $column;
			if ($datatype{$column} == "text" || $datatype{$column} == "varchar" || $datatype{$column} == "datetime") {
				if (substr_count($value, "\\'") > 0)
					$value = str_replace("\\'","''",$value);
				else
					$value = str_replace("'","''",$value);
				$colValues .= "'" . $value . "' ";
			}
			else
				$colValues .= $value;
		}
		$query .= $colNames . ") values (" . $colValues . ")";

	}
//echo $query;
	mysql_query("lock table " . $CONTENT_TYPE . " write") or die($_SERVER['PHP_SELF'] . ' Invalid query: ' . mysql_error() . "\n<br>" . $query);
	$result = mysql_query($query);
	
	if (!$result) {
		$error = true;

		switch (mysql_errno()) {
			case 1062:
				$MESSAGE = 2;
				break;
			default:
				die($_SERVER['PHP_SELF'] . ' Invalid query: ' . mysql_errno() . ': ' . mysql_error() . "\n<br>" . $query);
		}
	}
	
	$CONTENT = get_type($CONTENT_TYPE);

	if ($error) {
		mysql_query("unlock tables") or die($_SERVER['PHP_SELF'] . ' Invalid query: ' . mysql_error() . "\n<br>" . $query);
		$mode='edit';
		if ($CONTENT_ID > 0)
			$CONTENT->populate($CONTENT_ID);
	}
	else {
		if ($CONTENT_ID == 0) {
			$query = "select max(id) as newId from " . $CONTENT_TYPE ;
			$result = mysql_query($query) or die($_SERVER['PHP_SELF'] . ' Invalid query: ' . mysql_error() . "\n<br>" . $query);
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			$CONTENT_ID = $row{newId};
			mysql_query("unlock tables") or die($_SERVER['PHP_SELF'] . ' Invalid query: ' . mysql_error() . "\n<br>" . $query);
	
			$CONTENT->populate($CONTENT_ID);
			$MESSAGE = $CONTENT->initialise($CONTENT_ID, $parameter);
		}
		else
			mysql_query("unlock tables") or die($_SERVER['PHP_SELF'] . ' Invalid query: ' . mysql_error() . "\n<br>" . $query);
		
		$mode='display';
		$CONTENT->populate($CONTENT_ID);
	}

	include('templates/' . $TEMPLATE . '.php');
?>