<?php
// Class definition for request to join team
class Question extends Content
{
	// Constants
	const pwidth = 35;		// width of profile image
	const pheight = 49;		// height of profile image - also needs to be maintained in css rule for profileImage
	const rwidth = 150;		// width of detail image
	const rheight = 175;	// height of detail image - also needs to be maintained in css rule for requestDetailImage

	
    // property declaration
	public $contentType = 'question';
	public $author;
	public $status = 'New';
	public $updateDate = '';
	public $questionBody = '';
	public $answer='';

    // method declaration
	public function displayTitle() {
		echo "Question";
	}
	
	public function populate($id) {
		$query  = "select id, author_id, visible, status, question_body, answer, ";
		$query .= "date_format(create_date, '%l:%i%p %e %b') as sent, ";
		$query .= "date_format(update_date, '%l:%i%p %e %b') as updated ";
		$query .= "from question ";
		$query .= "where id = " . $id;
		$result = mysql_query($query) or die('Invalid query: ' . mysql_error() . "\n");
		
		$content = mysql_fetch_array($result, MYSQL_ASSOC);
		$this->id = $content{id};
		$this->authorId = $content{author_id};
		$this->visible = $content{visible};
		$this->status = $content{status};
		$this->createDate = $content{sent};
		$this->updateDate = $content{updated};
		$this->questionBody = $content{question_body};
		$this->answer = $content{answer};
		$this->author = new User();
		$this->author->populate($this->authorId);
	}
	
	public function display() {
		echo "<div id='main'>";

		backToList($this->contentType);
		
		if ($_SESSION{user_id} == $this->author->id) 
			authorControls($this->contentType, $this->id, $this->id, $_SESSION{user_id}, $this->visible, 'This question will be deleted.', 1, 'content');
			
		echo "<div class='messageImage'>";
			$scale = fitImageToDiv($this->author->getImage(), $this::pheight, $this::pwidth);
		echo "	<img src='" . $this->author->getImage() . "' " . $scale . " />";
		echo "</div>";
		echo "<h3>" . $this->author->username . "  " . $this->createDate . "</h3>";
		echo "<hr />";
		echo "<div class='requestMessage'>";
		echo $this->questionBody;
		echo "<p>" . $this->answer . "</p>";
		echo "</div></div>";
	}
	
	public function edit() {
		$qText = str_replace('"','&quot;',str_replace("'","&apos;", $this->questionBody));
		$aText = str_replace('"','&quot;',str_replace("'","&apos;", $this->answer));
		echo "<div id='main'>";
		echo "<form id='Fedit' name='Fedit' method='POST' action='save.php'>";
		echo "   <input type='hidden' name='tableName' value='question' />";
		echo "   <input type='hidden' name='id' value='" . $this->id . "' />";
		echo "   <input type='hidden' name='author_id' value='" . $_SESSION{user_id} . "' />";

		echo "   <div class='editfield'>";
		echo "      <label for='question_body'>Question</label>";
		echo "   	<textarea class='editBox' name='question_body' id='question_body' rows='10' cols='80'>" . $qText . "</textarea>";
		echo "   </div>";
		if ($_SESSION{user_id} == 1) {
			echo "   <div class='editfield'>";
			echo "      <label for='answer'>Answer</label>";
			echo "   	<textarea class='editBox' name='answer' id='answer' rows='10' cols='80'>" . $aText . "</textarea>";
			echo "   </div>";

			echo "   <div class='editfield'>";
			echo "      <label for='status'>Status</label>";
			echo "      <select name='status' id='status'>";
			echo "			<option value='New' ";
			if ($this->status == 'New')
				echo "selected='selected'";
			echo ">New</option>";
			echo "			<option value='Answered' ";
			if ($this->status == 'Answered')
				echo "selected='selected'";
			echo ">Answered</option>";
			echo "		</select>";
			echo "   </div>";			
		}
		echo "   <div class='formButtons'>";
		echo "		<a class='dialogButton' href='javascript:submitForm(\"Fedit\")'>Send</a>";
		echo "		<a class='dialogButton' href='javascript:submitForm(\"Fcancel\")'>Cancel</a>";
		echo "	 </div>";
		echo "</form>";
		echo "</div>";
		echo "<form id='Fcancel' name='Fcancel' method='POST' action='display.php'>";
		echo "   <input type='hidden' name='contentType' value='question' />";
		echo "   <input type='hidden' name='mode' value='list' />";
		echo "</form>";
	}

	public function preDelete() {
		if ($this->status == 'Answered') return 9;
		
		return 0;
	}
	
	public function listQuery() {
		$query  = "select id, author_id, visible, question_body as question, status ";
		$query .= "from question";
		return $query;
	}
	
	public function listNewControl() {
		echo "<div class='thinControls'>";
		echo "   <form id='Fgonew' name='Fgonew' method='POST' action='display.php'>";
		echo "      <input type='hidden' name='id' value='0' />";
		echo "      <input type='hidden' name='contentType' value='question' />";
		echo "      <input type='hidden' name='author_id' value='" . $_SESSION{user_id} . "' />";
		echo "      <input type='hidden' name='mode' value='edit' />";
		echo "   </form>";
		echo "   <div class='iconLink'>";
		echo "   <a onclick='javascript:submitForm(\"Fgonew\")'>";
		echo "      <img title='Ask a new question' alt='Ask a new question' src='images/add.png' />";
		echo "   </a></div>";
		echo "</div>";
	}
}
?>