<?php
// Class definition for request to join team
class Game extends Content
{
	// Constants
	const pwidth = 35;		// width of profile image
	const pheight = 49;		// height of profile image - also needs to be maintained in css rule for profileImage
	const rwidth = 150;		// width of detail image
	const rheight = 175;	// height of detail image - also needs to be maintained in css rule for requestDetailImage

	
    // property declaration
	public $contentType = 'game';
	public $name = '';
    public $latitude = 0;
	public $longitude = 0;
	public $zoom = 0;
	public $start='';
	public $end='';
	public $displayStart='';
	public $displayEnd='';
	public $startingNumber = 0;
	public $listText = "Click on a game to display the game monitor. Please note that the monitor does not work with Internet Explorer - use Chrome or Firefox.";

    // method declaration
	public function displayTitle() {
		echo $this->name;
	}
	
	public function populate($id) {
		$query  = "select id, author_id, visible, name, latitude, longitude, zoom, starting_number, ";
		$query .= "start, end, ";
		$query .= "date_format(start, '%l:%i%p %e %b') as display_start, ";
		$query .= "date_format(end, '%l:%i%p %e %b') as display_end ";
		$query .= "from game ";
		$query .= "where id = " . $id;
		$result = mysql_query($query) or die('Invalid query: ' . mysql_error() . "\n");
		
		$content = mysql_fetch_array($result, MYSQL_ASSOC);
		$this->id = $content{id};
		$this->authorId = $content{author_id};
		$this->visible = $content{visible};
		$this->name = $content{name};
		$this->latitude = $content{latitude};
		$this->longitude = $content{longitude};
		$this->zoom = $content{zoom};
		$this->start = $content{start};
		$this->end = $content{end};
		$this->displayStart = $content{display_start};
		$this->displayEnd = $content{display_end};
		$this->startingNumber = $content{starting_number};
	}
	
	public function display() {
		echo "<div id='main'>";

		backToList($this->contentType);
		if ($_SESSION{user_id} == $this->authorId) 
			authorControls($this->contentType, $this->id, $this->id, $_SESSION{user_id}, $this->visible, 'This game will be deleted.', 1, 'content');
			
		echo "<div id='mainContent'>";
		echo "<h3>" . $this->name . "</h3>";
		echo "<hr />";
		echo "<div class='display_field'><div class='displayLabel'>Latitude:</div>" . $this->latitude . "</div>";
		echo "<div class='display_field'><div class='displayLabel'>Longitude:</div>" . $this->longitude . "</div>";
		echo "<div class='display_field'><div class='displayLabel'>Zoom:</div>" . $this->zoom . "</div>";
		echo "<div class='display_field'><div class='displayLabel'>Start:</div>" . $this->displayStart . "</div>";
		echo "<div class='display_field'><div class='displayLabel'>End:</div>" . $this->displayEnd . "</div>";
		echo "<div class='display_field'><div class='displayLabel'>Starting number:</div>" . $this->startingNumber . "</div>";
		echo "</div></div></div>";
	}
	
	public function edit() {
		echo "<div id='main'>";
		echo "<fieldset><legend>Game details</legend>";
		echo "<form id='Fedit' name='Fedit' method='POST' action='save.php'>";
		echo "   <input type='hidden' name='tableName' value='game' />";
		echo "   <input type='hidden' name='id' value='" . $this->id . "' />";
		echo "   <input type='hidden' name='author_id' value='" . $_SESSION{user_id} . "' />";

		echo "   <div class='editfield'>";
		echo "   	<label for='name'>Name:</label>";
		echo "   	<input type='text' name='name' id='name' value='" . $this->name . "' />";
		echo "   </div><div class='editfield'>";
		echo "   	<label for='latitude'>Latitude:</label>";
		echo "   	<input type='text' name='latitude' id='latitude' value='" . $this->latitude . "' />";
		echo "   </div><div class='editfield'>";
		echo "   	<label for='longitude'>Longitude:</label>";
		echo "   	<input type='text' name='longitude' id='longitude' value='" . $this->longitude . "' />";
		echo "   </div><div class='editfield'>";
		echo "   	<label for='zoom'>Zoom:</label>";
		echo "   	<input type='text' name='zoom' id='zoom' value='" . $this->zoom . "' />";
		echo "   </div><div class='editfield'>";
		echo "   	<label for='start'>Start:</label>";
		echo "   	<input type='text' name='start' id='start' value='" . $this->start . "' />";
		echo "   </div><div class='editfield'>";
		echo "   	<label for='end'>End:</label>";
		echo "   	<input type='text' name='end' id='end' value='" . $this->end . "' />";
		echo "   </div><div class='editfield'>";
		echo "   	<label for='end'>Starting Number:</label>";
		echo "   	<input type='text' name='starting_number' id='starting_number' value='" . $this->startingNumber . "' />";
		echo "   </div>";
		echo "   <div class='formButtons'>";
		echo "		<a class='dialogButton' href='javascript:submitForm(\"Fedit\")'>Send</a>";
		echo "		<a class='dialogButton' href='javascript:submitForm(\"Fcancel\")'>Cancel</a>";
		echo "	 </div>";
		echo "</form>";
		echo "</fieldset>";
		echo "</div>";
		echo "<form id='Fcancel' name='Fcancel' method='POST' action='display.php'>";
		echo "   <input type='hidden' name='contentType' value='game' />";
		echo "   <input type='hidden' name='mode' value='list' />";
		echo "</form>";
	}
	
	public function listQuery() {
		$query  = "select id, author_id, visible, name, ";
		$query .= "date_format(start, '%l:%i%p %e %b') as start, date_format(end, '%l:%i%p %e %b') as end from game";
		return $query;
	}
	
	public function listNewControl() {
		if (isset($_SESSION{admin})) {
			echo "<div class='thinControls'>";
			echo "   <form id='Fgonew' name='Fgonew' method='POST' action='display.php'>";
			echo "      <input type='hidden' name='id' value='0' />";
			echo "      <input type='hidden' name='contentType' value='game' />";
			echo "      <input type='hidden' name='author_id' value='" . $_SESSION{user_id} . "' />";
			echo "      <input type='hidden' name='mode' value='edit' />";
			echo "   </form>";
			echo "   <div class='iconLink'>";
			echo "   <a onclick='javascript:submitForm(\"Fgonew\")'>";
			echo "      <img title='Create new game' alt='Create new game' src='images/add.png' />";
			echo "   </a></div>";
			echo "</div>";
		}
	}
	
	public function listControl() {
		echo "<a class='icon_link' target='_blank' href='monitor.php?gameId=" . $this->id . " >";
		echo "<img src='images/monitor.png />";
		echo "</a>";
	}
	
	public function reset() {
		$query = "update location set player_id = null, claimed = null, visible = 0 where game_id = " . $this->id;
		$result = mysql_query($query) or die("Unable to reset locations: " . mysql_error());

		// Reveal starting number of markers
		$query  = "select id, round(rand()) as ord_col ";
		$query .= "from   location ";
		$query .= "where  visible = 0 ";
		$query .= "and    game_id = " . $this->id . " ";
		$query .= "order by 2 limit " . $this->startingNumber;
		$result = mysql_query($query) or die("Unable to show initial locations: " . mysql_error());
		while ($loc = mysql_fetch_assoc($result)) {
			$query2  = "update location set visible = 1 ";
			$query2 .= "where  id = " . $loc{id} . " ";
			$query2 .= "and    game_id = " . $this->id;
			$result2 = mysql_query($query2) or die("Unable to update markers: " . mysql_error());
		}
	}

}
?>