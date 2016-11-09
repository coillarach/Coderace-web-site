<!DOCTYPE html>
<?php
	require_once('../connections/game.php');
	parse_str(http_build_query($_GET));
  
	echo "<html><head><title>Coderace</title>";
  
	// Get game initialisation details
	$query  = "select name, latitude, longitude, zoom ";
	$query .= "from   game ";
	$query .= "where  id = " . $gameId;
	$result = mysql_query($query) or die("Unable to execute game query: " . mysql_error());
	$game = mysql_fetch_assoc($result);
	
	echo "<script language='JavaScript'>";
	echo "	  var gameId = " . $gameId . ";";
	echo "    var gameName = '" . $game{name} . "';";
	echo "    var gameLatitude = " . $game{latitude} . ";";
	echo "    var gameLongitude = " . $game{longitude} . ";";
	echo "    var gameZoom = " . $game{zoom} . ";";
	echo "</script>";
?>
	    	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBS784jgxY3HvuLqhs60m-Nif8iPE884Zc&sensor=false"></script>
		<script type="text/javascript" src="../scripts/ajaxUtil.js"></script>
		<script type="text/javascript" src="../scripts/monitor.js"></script>
		<link href="../css/monitor.css" rel="stylesheet" type="text/css">
	</head>
	<body onLoad="initialise()">
		<div id="title_background" class="overlay"></div>
		<div id="title">
			<?php
				echo "Coderace: " . $game{name};
			?>
		</div>
		<div id="toggle">
			Show players 
			<input type='checkbox' id='playerCB' onChange='togglePlayers()'/>
		</div>
		<div id="map_canvas">Game monitor loading...</div> 
		<div id="gameSummary_background" class="overlay">
			<div class="floater"></div>
			<div id="gameSummary"></div>
		</div>
		<div id="timer"></div>
		<div id="alert_background" class="overlay">
			<div class="floater"></div>
			<div id="alertDiv"></div>
		</div>
		<div id="messageBackground" class="overlay">
			<div class="floater"></div>
			<div id="message"></div>
		</div>
	</body>
</html>