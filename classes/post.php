<?php
// Class definition for the simple article style content item
class Post extends Content
{
    // property declaration
	public $contentType = 'post';
	public $image = 'default.png';
    public $title = 'default title';
	public $heading = 'default heading';
	public $body = '<html>default body</html>';

    // method declaration
	public function displayTitle() {
		echo $this->title;
	}
	
	public function populate($id) {
		$query = "select id, author_id, image, title, heading, body from post where id = '" . $id . "'";
		$result = mysql_query($query);
		
		if (!$result) {
			$message = 'Invalid query: ' . mysql_error() . "\n";
			die($message);
		}
		
		$content = mysql_fetch_array($result, MYSQL_ASSOC);
		$this->id = $content{id};
		$this->authorId = $content{author_id};
		$this->image = $content{image};
		$this->title = $content{title};
		$this->heading = $content{heading};
		$this->body = $content{body};
	}
	
	public function display($author) {
		echo "<div id='main'>";

		if ($author == $this->authorId) 
			authorControls($this->contentType, $this->id, $this->id, $_SESSION{user_id}, 'This post will be deleted.', 1, 'post');
			
		echo "<h1>" . $this->heading . "</h1>";
		if ($this->image) {
			echo "<div id='post_image_div'>";
			echo "<img id='post_image' src='images/user/" . $this->authorId . "/" . $this->image . "' />";
			echo "</div>";
		}
		echo $this->body;
		echo "</div>";
	}
	
	public function edit() {
		$content = str_replace('"','&quot;',str_replace("'","&apos;", $this->body));
		echo "<div id='main'>";
		echo "<fieldset><legend>Edit post</legend>";
		echo "<form id='Fedit' name='Fedit' method='POST' action='save.php'>";
		echo "   <input type='hidden' id='tableName' name='tableName' value='post' />";
		echo "   <input type='hidden' id='id' name='id' value='" . $this->id . "' />";
		echo "   <div class='editfield'>";
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
		echo "   	<label for='body'>Body:</label>";
		echo "   	<textarea class='editBox' name='body' id='body' rows='10' cols='80'>" . $content . "</textarea>";
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
	
	public function listQuery() {
		return "select id, author_id, image, heading, create_date from content";
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