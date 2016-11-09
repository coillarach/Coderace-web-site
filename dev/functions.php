<?php

function logMessage($playerId, $message) {
	$file = fopen("../logs/" . $playerId . ".log","a");
	fwrite($file,date('j/m/y  h:i:s  ') . $message . "\n");
	fclose($file);
}

?>