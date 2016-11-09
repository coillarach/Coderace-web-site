<?php

require_once('../connections/game.php');#



$numrows = $_POST{rows};
$numcols = $_POST{cols};
$query = $_POST{query};
$result = mysql_query($query);

if (!$result) {
       	$message = 'Invalid query: ' . mysql_error() . "\n";
       	die($message);
}

if ($numcols == 0)
	$numcols = mysql_num_fields($result);


$first=true;

echo "<html><head><link rel='stylesheet' type='text/css' href='mondial.css'/></head><body>";

	if ($numrows != mysql_num_rows($result) || $numcols != mysql_num_fields($result)) 
		echo "<table class='answerTable_error'>";
	else {
		echo "<table class='answerTable'>";
		echo "<tr><td class='correct' colspan='" . mysql_num_fields($result) . "'>";
		echo "Correct!";
		echo "</td></tr>";
	}
		
	if ($numrows != mysql_num_rows($result)) {
		echo "<tr><td colspan='" . mysql_num_fields($result) . "'>";
		echo "ERROR: there should be " . $numrows . " row(s)";
		echo "</td></tr>";
	}

	if ($numcols != mysql_num_fields($result)) {
		echo "<tr><td colspan='" . mysql_num_fields($result) . "'>";
		echo "ERROR: there should be " . $numcols . " column(s)";
		echo "</td></tr>";
	}

while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

	if ($first) {
		echo "<tr>";
		foreach($row as $key => $value) {
			echo "<td class='colHead'>";
			echo $key;
			echo "</td>";
		}
		echo "</tr>";
		$first = false;
	}
	
	echo "<tr>";
	foreach($row as $key => $value) {
		echo "<td class='colValue'>";
		echo $value;
		echo "</td>";
	}
	echo "</tr>";
}

echo "</table></body></html>";

?>