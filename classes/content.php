<?php

// Content superclass
abstract class Content
{
    // property declaration
	public $id = 0;
	public $createDate = null;
	public $contentType = 'content';
	public $authorId = 0;
	public $visible = 1;
	public $listText = "";

    // method declaration
	abstract public function displayTitle();	// Echo an appropriate title for the page (property or string)
	abstract public function populate($id);		// Perform appropriate database query and assign retrieved values to object properties
	abstract public function display();			// Echo HTML to display object. Parameter is current user: controls display of controls
	abstract public function edit();			// Echo HTML to edit object. Includes an edit form and cancellation form

	public function initialise($id, $img) {				// Type-specific actions on object creation
		return 0;
	}

	private function preDelete() {				// Pre-delete checks. Returns a message id (messages in scripts/system.js)
		return 0;
	}
	
	public function delete() {					// Performs pre-delete checks. Deletes record from database
		$check = preDelete();
		if ($check > 0)
			return $check;
		
		$query = "delete from " . $this->contentType . " where id = " . $this->id;
		$result = mysql_query($query) or die($_SERVER['PHP_SELF'] . ' Could not delete ' . $this->contentType . ': ' . mysql_errno() . ': ' . mysql_error() . "\n<br>" . $query);
		return 0;
	}

	public function toggleVisible() {			// Toggles visibility of item - esp. in lists

		if ($this->visible == 1)
			$v = 0;
		else
			$v = 1;
			
		$query = "update " . $this->contentType . " set visible = " . $v . " where id = " . $this->id;

		$result = mysql_query($query) or die($_SERVER['PHP_SELF'] . ' Could not toggle visibility ' . $this->contentType . ': ' . mysql_errno() . ': ' . mysql_error() . "\n<br>" . $query);
		return 0;
	}

	public function listQuery() {				// Query used to populate content list
		$query  = "select * ";
		$query .= "from " . $this->contentType;
		return $query;
	}

	public function listNewControl() {			// Displays NEW control at the top of content list
		if (isset($_SESSION{admin})) {
			echo "<div class='thinControls'>";
			echo "   <form id='Fgonew' name='Fgonew' method='POST' action='display.php'>";
			echo "      <input type='hidden' name='id' value='0' />";
			echo "      <input type='hidden' name='contentType' value='" . $this->contentType +"' />";
			echo "      <input type='hidden' name='author_id' value='" . $_SESSION{user_id} . "' />";
			echo "      <input type='hidden' name='mode' value='edit' />";
			echo "   </form>";
			echo "   <div class='iconLink'>";
			echo "   <a onclick='javascript:submitForm(\"Fgonew\")'>";
			echo "      <img title='Create new " . $this->contentType . "' alt='Create new " . $this->contentType . "' src='images/add.png' />";
			echo "   </a></div>";
			echo "</div>";
		}
	}
}
?>