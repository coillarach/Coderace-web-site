<?php
	echo "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01//EN' 'http://www.w3.org/TR/html4/strict.dtd'>";
	echo "<html>";
	echo "	<head>";

	// Sets the window title from the content object
	echo "<title>";
	$CONTENT->displayTitle();
	echo "</title>";

	echo "<META HTTP-EQUIV='CACHE-CONTROL' CONTENT='NO-CACHE'>";
	echo "<META HTTP-EQUIV='PRAGMA' CONTENT='NO-CACHE'>";
	echo "<meta name='keywords' content='Android, orienteering, Edinburgh, cycling, bike, location-based, game, competition'>";
	echo "<meta name='description' content='Coderace is a location-based game where teams equipped with bikes and Android(tm) mobile devices compete to claim locations across the city of Edinburgh'>";
		
	echo "	<link rel='stylesheet' type='text/css' href='./css/coderace_layout.css' />";
	echo "	<link rel='stylesheet' type='text/css' href='./css/coderace_style.css' />";
	echo "	<script language='JavaScript' type='text/javascript' src='./scripts/system.js'></script>";

	echo "	<script language='JavaScript' type='text/javascript' src='./scripts/" . $CONTENT->contentType . ".js'></script>";

	echo "	<link rel='shortcut icon' href='icon.png'>";
		
	// Processes error messages and resets form values if there was an error.
	echo "<script language='JavaScript' type='text/javascript'>";
	echo "setMessage('" . $MESSAGE . "');";
	if ($error) {
		echo "setState('queryString', '" . http_build_query($_POST) . "');";
	}
	echo "</script>";
	unset ($MESSAGE);
		
	echo "</head>";
	echo "<body onload='initialise(\"" . $CONTENT->contentType . "\", " . $CONTENT->id . ", " . $CONTENT->authorId . ")' onresize='resizeBanner()'>";
	echo "	<div id='all'>";
	
	pageElement('login_links');
	pageElement('header');
			
//	pageElement('nav');
		
	echo "<div id='two_columns'>";
		
	pageElement('menu');
		
	echo "\n<!-------- CONTENT --------->\n";
	if ($mode == 'edit')
		$CONTENT->edit();
	else
		$CONTENT->display($_SESSION{user_id});
	echo "</div>";
		
	pageElement('footer');
	
	echo "	</div></body></html>";

?>