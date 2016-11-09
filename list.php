<?php

/* A list of all accessible items of specified type
 */

class ContentList {

    // property declaration
	public $id = 0;
	public $authorId = 0;
    public $contentType = 'content';
	public $contentObject = null;
	public $listHeaders = array();
	public $listContents = array();
	
    // method declaration
	public function __construct($pcontentType) {
		$this->contentType = $pcontentType;
		$this->contentObject = get_type($pcontentType);
	}
	
	public function displayTitle() {
		echo ucfirst($this->contentType) . "s";
	}

	public function populate() {
		$query = $this->contentObject->listQuery();
		$result = mysql_query($query) or die('Could not populate list: ' . mysql_error() . "\n");
		$count=0;
		while($item = mysql_fetch_array($result, MYSQL_ASSOC)) {
			if ($count == 0) {
				foreach ($item as $column => $value)
					$this->listHeaders[] = $column;
			}
			$this->listContents[] = $item;
			$count++;
		}				
	}

    public function display() {
		echo "<div id='main'>";
		echo "<div id='listPrologue'>";
		echo "<h1>" . ucfirst($this->contentType) . "s</h1>";
		echo $this->contentObject->listText;
		echo "</div>";
		echo "<div id='listInfo'>";
		echo "</div>";
		
		echo "<table class='contentList'><tr>";
		$col_count = 0;
		echo "<th></th>";
		foreach ($this->listHeaders as $col) {
			if ($col != "id" && 
			    $col != "author_id" && 
				$col != "visible" &&
				$col != "icon" && 
				$col != "default_icon" &&
				$col != "request_id" &&
				$col != "request_status")
				echo "<th>" . ucfirst($col) . "</th>";
			$col_count++;
		}
		// Right-hand column for control icons: new option conditionally displayed for different content types
		echo "<th>";
		$this->contentObject->listNewControl();
		echo "</th>"; 
		echo "</tr>";
		
		$count = 0;
		foreach ($this->listContents as $row) {
			if ($row{visible} == 1)
				echo "<tr>";
			else if (isset($_SESSION{admin}) || $row{author_id} == $_SESSION{user_id})
				echo "<tr class='invisible'>";
			else
				continue;
				
			if ($this->contentType == 'game')
				$onClickAction = "'javascript:monitor(" . $row{id} . ")'";
			else
				$onClickAction = "'javascript:submitForm(\"L" . $row{id} . "\")'";

			echo "<td onclick=" . $onClickAction . ">";
			echo "<div class='hidden'>";
			echo "<form name='Flist' id='L" . $row{id} . "' method='POST' action='display.php'>";
			echo "   <input type='hidden' name='id' value='" . $row{id} . "' />";
			echo "   <input type='hidden' name='contentType' value='" . $this->contentType . "' />";
			echo "   <input type='hidden' name='author_id' value='" . $row{author_id} . "' />";
			echo "   <input type='hidden' name='mode' value='display' />";
			echo "</form></div>";

			if ($row{icon})
				echo "<img class='list_icon' src='images/user/" . $row{author_id} . "/" . $row{icon} . "' />";
			else if ($row{default_icon})
				echo "<img class='list_icon' src='images/user/" . $row{default_icon} . "' />";
			else
				echo "<img class='list_icon' src='images/icon.png' />";
			echo "</a></td>";
			$col_count = 0;
			foreach ($row as $column => $value) {
				if ($column != "id" && 
				    $column != "author_id" && 
					$column != "visible" &&
					$column != "icon" && 
					$column != "default_icon"  &&
					$column != "request_id" &&
					$column != "request_status")
					echo "<td onclick=" . $onClickAction . ">" . $value . "</td>";
				$col_count++;
			}

			// Controls
			echo "<td class='" . $row_style . "'>";
			// Allow author to edit
			if ($_SESSION{user_id} == $row{author_id} || isset($_SESSION{admin}))
				authorControls($this->contentType, $this->id, $row{id}, $row{author_id}, $row{visible},'This item will be deleted.',1,'content');
			// If team request exists
			if ($row{request_id} > 0)
				viewRequestControl($row{id}, $row{request_id}, $row{author_id}, $row{request_status});
			// Game reset control
			if ($this->contentType == 'game' && isset($_SESSION{admin}))
				resetControl($row{id});
			echo "</td></tr>";
			$count++;
		}
		echo "</table></div>";
		if ($count == 1)
			$listCount = $count . " " . $this->content . " record found";
		else
			$listCount = $count . " " . $this->content . " records found";
			
		echo "<script language='JavaScript'>";
		echo "document.getElementById('listInfo').innerHTML = '" . $listCount . "';";
		echo "</script>";
	}
}
?>