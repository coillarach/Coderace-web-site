<?php
// Class definition for request to join team
class Request extends Content
{
	// Constants
	const pwidth = 35;		// width of profile image
	const pheight = 49;		// height of profile image - also needs to be maintained in css rule for profileImage
	const rwidth = 150;		// width of detail image
	const rheight = 175;	// height of detail image - also needs to be maintained in css rule for requestDetailImage

	
    // property declaration
	public $contentType = 'request';
	public $author;
	public $teamId = 0;
	public $teamName = '';
	public $teamLeader = 0;
	public $status = 'New';
	public $updateDate = '';
	public $message = "I'd like to join your team";

    // method declaration
	public function displayTitle() {
		echo $this->author->name;
	}
	
	public function populate($id) {
		$query  = "select r.id, r.author_id, r.visible, r.team_id, r.status, r.message, ";
		$query .= "       u.id as leader, t.name as team_name, ";
		$query .= "date_format(r.create_date, '%l:%i%p %e %b') as sent, ";
		$query .= "date_format(r.update_date, '%l:%i%p %e %b') as updated ";
		$query .= "from request r join team t on r.team_id = t.id ";
		$query .= "     join user u on u.id = t.author_id ";
		$query .= "where r.id = " . $id;
		$result = mysql_query($query) or die('Invalid query: ' . mysql_error() . "\n");
		
		$content = mysql_fetch_array($result, MYSQL_ASSOC);
		$this->id = $content{id};
		$this->authorId = $content{author_id};
		$this->visible = $content{visible};
		$this->teamId = $content{team_id};
		$this->teamName = $content{team_name};
		$this->teamLeader = $content{leader};
		$this->status = $content{status};
		$this->createDate = $content{sent};
		$this->updateDate = $content{updated};
		$this->message = $content{message};
		$this->author = new User();
		$this->author->populate($this->authorId);
	}
	
	public function display() {
		echo "<div id='main'>";

		if ($_SESSION{user_id} == $this->author->id) 
			authorControls($this->contentType, $this->id, $this->id, $_SESSION{user_id}, $this->visible, 'This request will be deleted.', $this->teamId, 'team');
			
		echo "<h1>Request from " . $this->author->username . "</h1>";
		echo "<h3>Sent " . $this->createDate . "</h3>";
		echo "<hr />";
		echo "<div class='messageImage'>";
			$scale = fitImageToDiv($this->author->getImage(), $this::pheight, $this::pwidth);
		echo "	<img src='" . $this->author->getImage() . "' " . $scale . " />";
		echo "</div>";
		echo "<div class='requestMessage'>";
		echo $this->message;
		echo "</div></div>";
	}
	
	public function edit() {
		$content = str_replace('"','&quot;',str_replace("'","&apos;", $this->message));
		echo "<div id='main'>";
		echo "<fieldset><legend>Message</legend>";
		echo "<form id='Fedit' name='Fedit' method='POST' action='save.php'>";
		echo "   <input type='hidden' name='tableName' value='request' />";
		echo "   <input type='hidden' name='id' value='" . $this->id . "' />";
		echo "   <input type='hidden' name='author_id' value='" . $_SESSION{user_id} . "' />";
		if ($this->teamId)
			echo "   <input type='hidden' name='team_id' value='" . $this->teamId . "' />";
		else
			echo "   <input type='hidden' name='team_id' value='" . $_POST{team_id} . "' />";
		echo "   <input type='hidden' name='status' value='New' />";
		echo "   <div class='editfield'>";
		echo "   	<textarea class='editBox' name='message' id='message' rows='10' cols='80'>" . $content . "</textarea>";
		echo "   </div>";
		echo "   </div><div class='formButtons'>";
		echo "		<a class='dialogButton' href='javascript:submitForm(\"Fedit\")'>Send</a>";
		echo "		<a class='dialogButton' href='javascript:submitForm(\"Fcancel\")'>Cancel</a>";
		echo "	 </div>";
		echo "</form></fieldset></div>";
		echo "<form id='Fcancel' name='Fcancel' method='POST' action='display.php'>";
		if ($this->teamId)
			echo "   <input type='hidden' name='id' value='" . $this->teamId . "' />";
		else
			echo "   <input type='hidden' name='id' value='" . $_POST{team_id} . "' />";
		echo "   <input type='hidden' name='contentType' value='team' />";
		echo "   <input type='hidden' name='mode' value='display' />";
		echo "</form>";
	}
	
	public function initialise($id, $parameter) {
		$leader = new User();
		$leader->populate($this->teamLeader);
		
		$leader->notify('request', '', 0);
	}

	public function preDelete() {
		if ($this->status == 'Accepted') return 3;

		return 0;
	}
	
	public function listDisplay() {
		echo "<div class='teamMember'>";
		echo "<h3>Request</h3>";
		$content  = "<div class='requestDetail'>";

		$content .= "<div id='requestDetailImage'>";
		$scale = fitImageToDiv($this->author->getImage(), $this::rheight, $this::rwidth);
		$content .= "<img src='" . $this->author->getImage() . "' " . $scale . "/>";
		$content .= "</div>";
		$content .= "<div id='requestDetailText'>";
		$content .= "<p>" . $this->author->firstName . " " . $this->author->lastName . " (" . $this->author->username . ")</p>";
		$content .= "<div class='email'>" . $this->author->email;
		$content .= "<div class='emailIcon'><div class='iconLink'><a href='mailto:" . $this->author->email . "'>";
		$content .= "<img src='images/email.png' /></a></div></div></div>";
		$content .= "<p>" . $this->message . "</p>";
		$content .= "<p>" . $this->author->comment . "</p>";
		$content .= "</div></div>";
		echo "<a href='javascript:teamMemberComment(" . $this->authorId . ", \"" . urlencode($content) . "\", " . $this->id . ", " . $_SESSION{user_id} . ")' >";
		echo "<div class='requestImage'>";
		$scale = fitImageToDiv($this->author->getImage(), $this::rheight, $this::rwidth);
		echo "	<img src='" . $this->author->getImage() . "' " . $scale . " />";
		echo "</div>";
		echo "<div class=messageField'>" . $this->author->username . "</div>";
		echo "<div class=messageField'>" . $this->createDate . "</div>";
		echo "</a>";
		echo "<div name='speechPoint' id='p" . $this->authorId . "' class='speechPoint'> <img src='images/point.png' /> </div>";
		echo "</div>";
	}

	public function accept() {
		// Update status on request record
		$query = "update request set status = 'Accepted', update_date = current_timestamp where id = " . $this->id;
		$result = mysql_query($query) or die($_SERVER['PHP_SELF'] . ' Invalid query: ' . mysql_errno() . ': ' . mysql_error() . "\n<br>" . $query);
	
		// Update team membership of request author
		$query = "update user set team_id = " . $this->teamId . " where id = " . $this->authorId;
		$result = mysql_query($query) or die($_SERVER['PHP_SELF'] . ' Invalid query: ' . mysql_errno() . ': ' . mysql_error() . "\n<br>" . $query);
		
		// Notify player
		$player = new User();
		$player->populate($this->authorId);
		$player->notify('accept',$this->teamName, $this->teamLeader);
	}

	public function reject() {
		// Update status on request record
		$query = "update request set status = 'Rejected', update_date = current_timestamp where id = " . $this->id;
		$result = mysql_query($query) or die($_SERVER['PHP_SELF'] . ' Invalid query: ' . mysql_errno() . ': ' . mysql_error() . "\n<br>" . $query);

		// Notify player
		$player = new User();
		$player->populate($this->authorId);
		$player->notify('reject',$this->teamName, 1);
	}
}
?>