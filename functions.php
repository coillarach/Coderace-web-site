<?php

function absolute_url ($page = 'index.php') {
	// URL is http:// plus hostname plus current directory
	$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);

	// Remove any trailing slashes
	$url=rtrim($url,'/\\');

	// Add the page
	$url .= '/' . $page;

	// Return the url
	return $url;

}

function list_directory($dir) {
	$files = array();
	if ($handle = opendir($dir)) {

		/* This is the correct way to loop over the directory. See http://php.net/manual/en/function.readdir.php */
		while (false !== ($entry = readdir($handle))) {
			if ($entry == "." || $entry == "..")
				continue;
			$files[] = $entry;
		}
		closedir($handle);
	}
	return $files;
}

function pageElement($part) {
	echo "\n<!-------- " . $part . " --------->\n";
	echo "<div id='" . $part . "'>";
	include('parts/' . $part . ".php"); 
	echo "</div>";
}

function get_type( $class ) {
	switch ($class) {
	case 'document':
		$object = new Document();
		break;
	case 'user':
		$object = new User();
		break;
	case 'team':
		$object = new Team();
		break;
	case 'request':
		$object = new Request();
		break;
	case 'question':
		$object = new Question();
		break;
	case 'game':
		$object = new Game();
		break;
	case 'stats':
		$object = new Stats();
		break;
	}
	return $object;
}

function scale_factor($fname, $h, $w) {
	list($width, $height, $type, $attr) = getimagesize($fname);
	$vscale = $h/$height;
	$hscale = $w/$width;
	if ($vscale < $hscale)
		$retval = array(round($vscale * 100), $height, $width);
	else
		$retval = array(round($hscale * 100), $height, $width);
	return $retval;
}

function fitImageToDiv($img, $h, $w) {
	list($width, $height, $type, $attr) = getimagesize($img);
	// if image aspect ratio (w/h) > div aspect ratio, scale by width
	if ($width/$height > $w/$h)
		return " style='width: 100%; height: auto;' ";
	else
		return " style='height: 100%; width: auto;' ";
}

// Scales all images in a document to specified size
function inset_images($txt, $h, $w) {
	$in_txt = str_split($txt);
	$out_txt = "";
	$buffer = "";
	$i=0;
	while ($i < sizeof($in_txt)) {
		if ($in_txt[$i] == '<') {
			$buffer = $in_txt[$i];
			$i++;
			while ($in_txt[$i] != '>') {
				$buffer .= $in_txt[$i];
				$i++;
			}
			// Add final '>'
			$buffer .= $in_txt[$i];

			if (substr_count($buffer, '<img ') > 0 && substr_count($buffer, "class='inset") > 0) {
				$fname = strtok(strstr($buffer, "src="), "'");
				$fname = strtok("'");
				list($factor, $height, $width) = scale_factor($fname, $h, $w);
				$out_txt .= "<a href='javascript:imageDialog(\"" . $fname . "\", " . $height . ", " . $width . ")'>";
				if (substr_count($buffer, "inset_right") > 0)
					$out_txt .= "<img class='inset_right' src='images/magnifier_zoom_in.png' />";
				else
					$out_txt .= "<img class='inset_left' src='images/magnifier_zoom_in.png' />";
				$out_txt .= "<img style='width: " . $factor . "%;' " . substr($buffer, 5);
				$out_txt .= "</a>";
			}
			else
				$out_txt .= $buffer;
		}
		else
			$out_txt .= $in_txt[$i];
		$i++;
	}
	return $out_txt;
}

function push($id, $contentType, $authorId, $mode) {
	$_SESSION{id} = $id;
	$_SESSION{contentType} = $contentType;
	$_SESSION{authorId} = $authorId;
	$_SESSION{mode} = $mode;
}

function hiddenForm($id, $targetId, $contentType, $mode) {
	$formname = "F" . $id;
	echo "<div class='hidden'>";
	echo "<form name='" . $formname . "' id='" . $formname . "' method='POST' action='display.php'>";
	echo "   <input type='hidden' name='id' value='" . $targetId . "' />";
	echo "   <input type='hidden' name='contentType' value='" . $contentType . "' />";
	// These fields populated by javascript resizeBanner()
	echo "	 <input type='hidden' name='windowHeight' value='' />";
	echo "	 <input type='hidden' name='windowWidth' value='' />";
	if ($mode == "list")
		echo "   <input type='hidden' name='mode' value='list' />";
	elseif ($targetId == 0)
		echo "   <input type='hidden' name='mode' value='edit' />";
	else
		echo "   <input type='hidden' name='mode' value='display' />";
	echo "</form></div>";
			
	return $formname;
}

function jumpLink($thisId, $thisContentType, $thisAuthorId, $mode, $targetId, $targetContentType) {
	// Save POST values to be reset on going back
	echo "<script language='JavaScript' type='text/javascript'>";
	echo "setState('queryString', '" . http_build_query($_POST) . "');";
	echo "</script>";
	// Save information about return target in the SESSION
	push($thisId, $thisContentType, $thisAuthorId, $mode);
	
	return hiddenForm($thisId, $targetId, $targetContentType, 'display');
}

function backToList($contentType) {
	echo "<div class='controls'>";
	echo "<div class='iconLink'>";
	echo "<a class='menu_item' href='#' onclick='javascript:submitForm(\"" . hiddenForm(0,0,$contentType,'list') . "\")' >";
	echo "<img title='Close' alt='Close' src='images/gray_cross.png' />";
	echo "</a></div>";
	echo "</div>";
}

function authorControls($content_type, $list_flag, $content_id, $content_author, $visible, $deleteMessage, $related_id=1, $related_type='content') {
	if (!isset($deleteMessage))
		$deleteMessage = 'This ' . $content_type . " will be deleted.";
	// Toggle visibility
	echo "<div class='controls'>";
	echo "   <form id='FV" . $content_id . "' name='FV" . $content_id . "' method='POST' action='toggleVisibility.php'>";
	echo "      <input type='hidden' name='id' value='" . $content_id . "' />";
    echo "      <input type='hidden' name='tableName' value='" . $content_type . "' />";
	echo "      <input type='hidden' name='listFlag' value='" . $list_flag . "' />";
	echo "   </form>";
	echo "   <div class='iconLink'>";
	echo "   <a onclick='javascript:submitForm(\"FV" . $content_id . "\")'>";
	if ($visible == 1)
		echo "      <img title='Hide' alt='Hide' src='images/lightbulb_off.png' />";
	else
		echo "      <img title='Show' alt='Show' src='images/lightbulb.png' />";
	echo "   </a></div>";
	echo "</div>";
	
	// Delete
	echo "<div class='controls'>";
	echo "   <form id='FD" . $content_id . "' name='FD" . $content_id . "' method='POST' action='delete.php'>";
	echo "      <input type='hidden' name='id' value='" . $content_id . "' />";
    echo "      <input type='hidden' name='tableName' value='" . $content_type . "' />";
	echo "      <input type='hidden' name='listFlag' value='" . $list_flag . "' />";
	echo "      <input type='hidden' name='successItemId' value='" . $related_id . "' />";
    echo "      <input type='hidden' name='successItemType' value='" . $related_type . "' />";
	echo "   </form>";
	echo "   <div class='iconLink'>";
	echo "   <a onclick=\"javascript:formConfirm('" . $deleteMessage . "<br />Continue?', 'submitForm(\\'FD" . $content_id . "\\')','No','Yes','100px','200px')\">";
	echo "      <img title='Delete' alt='Delete' src='images/delete.png' />";
	echo "   </a></div>";
	echo "</div>";
	// Edit
	echo "<div class='controls'>";
	echo "   <form id='FE" . $content_id . "' name='FE" . $content_id . "' method='POST' action='display.php'>";
	echo "      <input type='hidden' name='id' value='" . $content_id . "' />";
    echo "      <input type='hidden' name='contentType' value='" . $content_type . "' />";
    echo "      <input type='hidden' name='author_id' value='" . $content_author . "' />";
	echo "      <input type='hidden' name='mode' value='edit' />";
	echo "   </form>";
	echo "   <div class='iconLink'>";
	echo "   <a onclick='javascript:submitForm(\"FE" . $content_id . "\")'>";
	echo "      <img title='Edit' alt='Edit' src='images/page_edit.png' />";
	echo "   </a></div>";
	echo "</div>";
}

function viewRequestControl($team_id, $content_id, $author_id, $status) {
	echo "<div class='controls'>";
	echo "   <form id='Fgoview' name='Fgoview' method='POST' action='display.php'>";
	echo "      <input type='hidden' name='id' value='" . $team_id . "' />";
    echo "      <input type='hidden' name='contentType' value='team' />";
    echo "      <input type='hidden' name='author_id' value='" . $author_id . "' />";
	echo "      <input type='hidden' name='mode' value='display' />";
	echo "   </form>";
	echo "   <div class='iconLink'>";
	echo "   <a href='javascript:submitForm(\"Fgoview\")'>";
	switch ($status) {
		case 'New':
			echo "      <img title='Waiting for response to join request' alt='Waiting for response to join request' src='images/flag_green.png' />";
			break;
		case 'Accepted':
			echo "      <img title='You&apos;re on the team!' alt='You&apos;re on the team!' src='images/tick.png' />";
			break;
		case 'Rejected':
			echo "      <img title='Request to join rejected' alt='Request to join rejected' src='images/cross.png' />";
			break;
	}
	echo "   </a></div>";
	echo "</div>";
}

function resetControl($content_id) {
	echo "<div class='controls'>";
	echo "   <form id='FR" . $content_id . "' name='FR" . $content_id . "' method='POST' action='reset.php'>";
	echo "      <input type='hidden' name='id' value='" . $content_id . "' />";
	echo "   </form>";
	echo "   <div class='iconLink'>";
	echo "   <a onclick=\"javascript:formConfirm('Reset this game?', 'submitForm(\\'FR" . $content_id . "\\')','No','Yes','100px','200px')\">";
	echo "      <img title='Reset' alt='Reset' src='images/arrow_undo.png' />";
	echo "   </a></div>";
	echo "</div>";
}

?>