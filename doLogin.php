<?php
	require_once('global.php');
	
	// Check the email and password in the database
	$query  = "SELECT id, first_name, last_name, username, status, team_id "; // team_id is custom for Coderace
	$query .= "from user ";
	$query .= "where username = '" . $_POST{login_username} . "' ";
	$query .= "and password = '" . sha1($_POST{login_password}) . "'";
    $result = mysql_query($query) or  die('Could not query user details: ' . mysql_error());

	// If no result is returned, the details are incorrect
	// Redirect to the login page with an error code
	$count=mysql_num_rows($result);
	if ($count == 0)
		header('Location: ' . absolute_url('index.php?o=1'));
	else {
		$details = mysql_fetch_array($result, MYSQL_ASSOC);

		// Start a session and save the user's id
		session_start();
		$_SESSION{user_id} 			= $details{id};
		$_SESSION{user_first_name}	= $details{first_name};
		$_SESSION{user_last_name}	= $details{last_name};
		$_SESSION{username}			= $details{username};
		if ($details{username} == 'Admin')
			$_SESSION{admin} = 'Y';							// Temporary test
		$_SESSION{status}			= $details{status};
		$_SESSION['LAST_ACTIVITY']	= time();				// update last activity time stamp	- checked in display.php
		$_SESSION{team_id}			= $details{team_id};	// Custom for Coderace

		// Log visit
		$query  = "insert into login (user_id, session_id, ip) ";
		$query .= "values ('" . $_SESSION{user_id} . "', ";
		$query .= "        '" . session_id() . "', ";
		$query .= "        '" . $_SERVER{REMOTE_ADDR} . "')";
		$result = mysql_query($query) or die('Could not record login ' . mysql_error());
		
		// Redirect to home page
		header('Location: ' . absolute_url('index.php'));
	}

    mysql_close($link);
?>