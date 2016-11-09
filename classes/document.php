<?php
// Class definition for the simple article style content item
class Document extends Content
{
    // property declaration
	public $contentType = 'document';
	public $image = 'default.png';
	public $name = 'default name';
    public $title = 'default title';
	public $heading = 'default heading';
	public $html = '<html>default html</html>';

    // method declaration
	public function displayTitle() {
		echo $this->title;
	}
	
	public function populate($id) {
		$query = "select id, author_id, visible, image, name, title, heading, html from document where id = '" . $id . "'";
		$result = mysql_query($query);
		
		if (!$result) {
			$message = 'Invalid query: ' . mysql_error() . "\n";
			die($message);
		}
		
		$content = mysql_fetch_array($result, MYSQL_ASSOC);
		$this->id = $content{id};
		$this->authorId = $content{author_id};
		$this->visible = $content{visible};
		$this->image = $content{image};
		$this->name = $content{name};
		$this->title = $content{title};
		$this->heading = $content{heading};
		$this->html = $content{html};
	}
	
	public function display() {
		echo "<div id='main'>";

		if ($_SESSION{user_id} == $this->authorId) 
			authorControls($this->contentType, $this->id, $this->id, $_SESSION{user_id}, $this->visible, 'This document will be deleted.', 1, 'document');
			
		echo "<h1>" . $this->heading . "</h1>";
		if ($this->image) {
			echo "<div id='main_image_div'>";
			echo "<img id='main_image' src='images/user/" . $this->authorId . "/" . $this->image . "' />";
			echo "</div>";
		}
		echo inset_images($this->html, 60, 50);
		echo "</div>";
	}
	
	public function edit() {
		$content = str_replace('"','&quot;',str_replace("'","&apos;", $this->html));
		echo "<div id='main'>";
		echo "<fieldset><legend>Edit document</legend>";
		echo "<form id='Fedit' name='Fedit' method='POST' action='save.php'>";
		echo "   <input type='hidden' id='tableName' name='tableName' value='document' />";
		echo "   <input type='hidden' id='id' name='id' value='" . $this->id . "' />";
		echo "   <div class='editfield'>";
		echo "   	<label for='name'>Name:</label>";
		echo "   	<input type='text' name='name' id='name' value='" . $this->name . "' />";
		echo "   </div><div class='editfield'>";
		echo "   	<label for='title'>Title:</label>";
		echo "   	<input type='text' name='title' id='title' value='" . $this->title . "' />";
		echo "   </div><div class='editfield'>";
		echo "   	<label for='heading'>Heading:</label>";
		echo "   	<input type='text' name='heading' id='heading' value='" . $this->heading . "' />";
		echo "   </div><div class='editfield'>";
		echo "   	<label for='image'>Image:</label>";
		echo "   	<input type='text' name='image' id='image' value='" . $this->image . "' />";
		echo "   </div><div class='editfield'>";
		echo "      <br />";
		echo "   	<label for='html'>HTML:</label>";
		echo "   	<textarea class='editBox' name='html' id='html' rows='10' cols='80'>" . $content . "</textarea>";
		echo "   </div><div class='formButtons'>";
		echo "   	<input type='submit' value='Submit'>";
		echo "   	<input type='button' value='Cancel' onClick='submitForm(\"Fcancel\")'>";
		echo "	 </div>";
		echo "</form></fieldset></div>";
		echo "<form id='Fcancel' name='Fcancel' method='POST' action='display.php'>";
		echo "   <input type='hidden' name='id' value='" . $this->id . "' />";
		echo "   <input type='hidden' name='mode' value='display' />";
		echo "</form>";
	}
	
	private function preDelete() {				// Pre-delete checks. Returns a message id (messages in scripts/system.js)
		if ($this->id == 1) return 6;
			
		return 0;
	}
	
	public function listQuery() {
		return "select id, author_id, visible, image, heading, create_date from content";
	}
	
	public function listNewControl() {
		if (!isset($_SESSION{admin})) {
			echo "<div class='thinControls'>";
			echo "   <form id='Fgonew' name='Fgonew' method='POST' action='display.php'>";
			echo "      <input type='hidden' name='id' value='0' />";
			echo "      <input type='hidden' name='contentType' value='document' />";
			echo "      <input type='hidden' name='author_id' value='" . $_SESSION{user_id} . "' />";
			echo "      <input type='hidden' name='mode' value='edit' />";
			echo "   </form>";
			echo "   <div class='iconLink'>";
			echo "   <a onclick='javascript:submitForm(\"Fgonew\")'>";
			echo "      <img title='Set up a new team' alt='Set up a new team' src='images/add.png' />";
			echo "   </a></div>";
			echo "</div>";
		}
	}

}
?>