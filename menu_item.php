<?php
class MenuItem
{
    // property declaration
    public $id = 0;
	public $label = "default label";
	public $parentId = 0;
	public $children = 0;
	public $level = 0;
	public $contentId = 0;
	public $contentType = 'default';
	public $publicFlag = 'N';

    // method declaration
	public function __construct($pid, $plabel, $pparentId, $pchildren, $plevel, $paction, $pcontentId, $pcontentType, $ppublicFlag) {
		$this->id = $pid;
		$this->label = $plabel;
		$this->parentId = $pparentId;
		$this->children = $pchildren;
		$this->level = $plevel;
		$this->action = $paction;
		$this->contentId = $pcontentId;
		$this->contentType = $pcontentType;
		$this->publicFlag = $ppublicFlag;
	}

    public function display() {
		echo str_repeat( "&nbsp;", 3*$this->level );
		
		if (!isset($this->contentId)) {
			switch ($this->contentType) {
				case 'user': $this->contentId = $_SESSION{user_id};
							 break;
				case 'team': $this->contentId = $_SESSION{team_id};
							 break;
			}
		}

		if (isset($this->contentId) || $this->action == "list") {
			echo "<div class='hidden'>";
			echo "<form name='Fmenu' id='F" . $this->id . "' method='POST' action='display.php'>";
			echo "   <input type='hidden' name='id' value='" . $this->contentId . "' />";
			echo "   <input type='hidden' name='contentType' value='" . $this->contentType . "' />";
			// These fields populated by javascript initialise()
			echo "	 <input type='hidden' name='windowHeight' value='' />";
			echo "	 <input type='hidden' name='windowWidth' value='' />";
			if ($this->action == "list")
				echo "   <input type='hidden' name='mode' value='list' />";
			elseif ($this->contentId == 0)
				echo "   <input type='hidden' name='mode' value='edit' />";
			else
				echo "   <input type='hidden' name='mode' value='display' />";
			echo "</form></div>";
			echo "<a class='menu_item' href='#' onclick='javascript:submitForm(\"F" . $this->id . "\")' >";
			echo $this->label;
			echo "</a><br>";
		}
		elseif ($this->children > 0)
			echo "<span class='menu_category'>" . $this->label . "</span><br>";
		else
			echo "<span class='menu_gap'>" . $this->label . "</span><br>";
    }	
}
?>