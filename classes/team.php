<?php
// Class definition for the simple article style content item
class Team extends Content
{
	// Constants
	const pwidth = 150;		// width of team image
	const pheight = 175;	// height of team image - uses same css rules as user profile image (profileImage)
	const mwidth = 100;		// width of member image
	const mheight = 120;	// height of member image
	
    // property declaration
	public $contentType = 'team';
	public $name = '';
    public $motto = '';
	public $status = '';
	public $comment = '';
	public $image = '';
	public $members = array();
	public $requestId = 0;
	public $requestStatus = '';
	public $requests = array();

    // method declaration
	public function displayTitle() {
		echo $this->name;
	}
	
	public function populate($id) {
		$query  = "select t.id, t.author_id, t.visible, t.name, t.motto, t.status, t.comment, ";
		$query .= "ifnull(t.image,'default.png') as pic, t.create_date, ";
		$query .= "ifnull(r.id, 0) as request_id, r.status as request_status ";
		$query .= "from team t left join request r on (t.id = r.team_id and r.author_id = " . $_SESSION{user_id} . ") ";
		$query .= "where t.id = " . $id;
		$result = mysql_query($query) or die('Invalid query: ' . mysql_error() . "\n");
		
		$content = mysql_fetch_array($result, MYSQL_ASSOC);
		$this->id = $content{id};
		$this->authorId = $content{author_id};
		$this->visible = $content{visible};
		$this->name = $content{name};
		$this->motto = $content{motto};
		$this->status = $content{status};
		$this->comment = $content{comment};
		$this->image = $content{pic};
		$this->createDate = $content{create_date};
		$this->requestId = $content{request_id};
		$this->requestStatus = $content{request_status};

		$query = "select id from user where team_id = " . $id;
		$result = mysql_query($query) or die('Invalid query: ' . mysql_error() . "\n");
		$count=0;
		while($member = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->members[] = new User();
			$this->members[$count]->populate($member{id});
			$count++;
		}

		$query = "select id from request where status = 'New' and team_id = " . $id;
		$result = mysql_query($query) or die('Invalid query: ' . mysql_error() . "\n");
		$count=0;
		while($request = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->requests[] = new Request();
			$this->requests[$count]->populate($request{id});
			$count++;
		}
	}
	
	public function display() {
		echo "<div id='main'>";
		echo "<div id='teamFields'>";
		
		backToList($this->contentType);

		if ($_SESSION{user_id} == $this->authorId) 
			authorControls($this->contentType, $this->id, $this->id, $this->authorId, $this->visible, 'This team will be deleted.', 1, 'content');
		echo "<div id='profileImage'>";

		if ($this->image) {
//			list($width, $height, $type, $attr) = getimagesize($this->image);
			list($width, $height, $type, $attr) = getimagesize("images/user/" . $this->authorId . "/" . $this->image);
			if ($width/$this::pwidth >= $height/$this::pheight)
				$scale = " style='width: 100%; height: auto;' ";
			else
				$scale = " style='height: 100%; width: auto;' ";
			echo "<img id='main_image' src='images/user/" . $this->authorId . "/" . $this->image . "' " . $scale . "/>";
		}
		else
			echo "<img id='main_image' src='images/user/default.png' />";
		echo "</div>";

		echo "<h1>";
		echo $this->name;
		echo "</h1>";
		echo "<div class='display_field'><h3>";
		echo $this->motto;
		echo "</h3></div>";
		echo "<div class='inlineDisplayField'>";
		echo "Status: " . $this->status;
		echo "</div>";
		if (!$_SESSION{team_id} && $this->status == 'Recruiting') {
			if ($this->requestId == 0) {
				$this->requestButton('', 0, 'edit', $this->id, 'Request to join');
			}
			elseif ($this->requestStatus == 'New') {
				$this->requestButton('Waiting for response to join request', $this->requestId, 'display', $this->id, 'View');
			}
		}
		echo "<div class='display_text'>";
		echo $this->comment;
		echo "</div></div>";
		echo "<h4>Click an image for member details</h4>";
		echo "<hr/>";
		foreach ($this->members as $member) {
			$mscale = fitImageToDiv($member->getImage(), $this::mheight, $this::mwidth);

			echo "\n\n<div class='teamMember'>";
			$teamAuthor = ($this->authorId == $_SESSION{user_id}) ? $this->authorId : 0;

			$content  = "<div class='email'>" . $member->email;
			$content .= "<div class='emailIcon'>";
			$content .= "<div class='iconLink'><a href='mailto:" . $member->email . "'>";
			$content .= "<img src='images/email.png' /></a></div>";
			$content .= "</div></div>";
			$content .= $member->comment;
			
			echo "\n  <a href='javascript:teamMemberComment(" . $member->id . ", \"" . urlencode($content) . "\", 0, " . $teamAuthor . ")' >";
			echo "<div class='teamMemberImage'>";
			echo "\n    <img class='memberImage' src='" . $member->getImage() . "' " . $mscale . " />";
			echo "</div>";
			echo "\n    <div class='memberLabel'>";
			echo $member->username;
			echo "</div>";
			echo "\n  </a>";
			echo "\n  <div name='speechPoint' id='p" . $member->id . "' class='speechPoint'> <img src='images/point.png' /> </div>";
			echo "\n</div>";
		}

		// Display join requests only for author
		if ($this->userIsAuthor()) {
			echo "\n\n<div class='requestList'>";
			foreach ($this->requests as $request) {
				$request->listDisplay();
			}
			echo "</div>";
		}

		echo "<div id='bubbleBody' class='bubbleBody'>";
		echo "</div>";
		echo "</div>";
	}
	
	// Assumes that for a new item, id is set to 0
	public function edit() {
		$content = str_replace('"','&quot;',str_replace("'","&apos;", $this->comment));
		echo "<div id='main'>";
		echo "<fieldset><legend>Team details</legend>";
		echo "<form id='Fedit' name='Fedit' method='POST' action='save.php'>";
		echo "   <input type='hidden' id='tableName' name='tableName' value='team' />";
		echo "   <input type='hidden' id='id' name='id' value='" . $this->id . "' />";
		echo "   <input type='hidden' id='author_id' name='author_id' value='" . $_SESSION{user_id} . "' />";

		echo "<iframe id='profileImage' name='profileImage' scrolling='no'></iframe>";

		echo "	<div id='teamFields'>";
		echo "   <div class='editfield'>";
		echo "   	<label for='name'>Name:</label>";
		echo "   	<input type='text' name='name' id='name' value='" . $this->name . "' />";
		echo "   </div><div class='editfield'>";
		echo "   	<label for='motto'>Motto:</label>";
		echo "   	<input type='text' name='motto' id='motto' value='" . $this->motto . "' />";
		echo "   </div><div class='editfield'>";
		echo "   	<label for='status'>Status:</label>";
		echo "      <select name='status' id='status'>";
		echo "			<option value='Recruiting' ";
		if ($this->status == 'Recruiting')
			echo "selected='selected'";
		echo ">Recruiting</option>";
		echo "			<option value='Not recruiting' ";
		if ($this->status == 'Not recruiting')
			echo "selected='selected'";
		echo ">Not recruiting</option>";
		echo "		</select>";
		echo "   </div><div class='editfield'>";
		echo "   	<label for='image'>Image:</label>";
		echo "		<select name='image' id='image' onChange='updateProfileImage(" . $_SESSION{user_id} . ", this)'>";
		echo "		<option value=''>Select image file</option>";
		foreach (list_directory("images/user/" . $_SESSION{user_id} . "/") as $file) {
			echo "		<option value='" . $file . "' ";
			if ($file == $this->image)
				echo "selected='selected'";
			echo ">" . $file . "</option>";
		}
		echo "		</select>";
		echo "		<input type='button' id='imgUpload' name='imgUpload' value='Upload image' onClick='imageUploadDialog(state.message, 80, 300)' />";
		echo "      </div></div>";
		echo "   	<label for='comment'>Manifesto:</label>";
		echo "   	<textarea class='editBox' name='comment' id='comment' rows='10' cols='80'>" . $content . "</textarea>";
		echo "   	<div class='formButtons'>";
		echo "   	<input type='submit' value='Submit'>";
		echo "   	<input type='button' value='Cancel' onClick='submitForm(\"Fcancel\")'>";
		echo "	 </div>";
		echo "</form></fieldset></div>";
		echo "<form id='Fcancel' name='Fcancel' method='POST' action='display.php'>";
		echo "   <input type='hidden' name='id' value='0' />";
		echo "   <input type='hidden' name='contentType' value='team' />";
		echo "   <input type='hidden' name='mode' value='list' />";
		echo "</form>";
	}

	public function initialise($id, $img) {
		// Locks are released again in save.php
		mysql_query("unlock tables") or die($_SERVER['PHP_SELF'] . ' Invalid query: ' . mysql_error() . "\n<br>" . $query);
		mysql_query("lock table user write, team read") or die($_SERVER['PHP_SELF'] . ' Invalid query: ' . mysql_error() . "\n<br>" . $query);

		// Update team membership
		$query = "update user set team_id = " . $id . " where id = (select author_id from team where id = " . $id . ")";
		$result = mysql_query($query) or die($_SERVER['PHP_SELF'] . ' Invalid query: ' . mysql_error() . "\n<br>" . $query);

		// Sets session variable to hide menu item
		$_SESSION{team_id}=$this->id;
	}

	public function preDelete() {
		if (sizeof($this->members) > 0) return 4;

		return 0;
	}
	
	public function listQuery() {
		$query  = "select t.id, t.author_id, t.visible, t.name, t.motto, ";
		$query .= "       t.image as icon, 'icon.png' as default_icon, ";
		$query .= "       ifnull(r.id, 0) as request_id, r.status as request_status ";
		$query .= "from team t left join request r on (t.id = r.team_id and r.author_id = " . $_SESSION{user_id} . ") ";
		return $query;
	}
	
	public function listNewControl() {
		if (!isset($_SESSION{team_id})) {
			echo "<div class='thinControls'>";
			echo "   <form id='Fgonew' name='Fgonew' method='POST' action='display.php'>";
			echo "      <input type='hidden' name='id' value='0' />";
			echo "      <input type='hidden' name='contentType' value='team' />";
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
	
	private function requestButton($legend, $id, $mode, $teamId, $label) {
		echo "<div class='linkButton'>";
		echo "<div class='hidden'>";
		echo "<form name='Frequest' id='Frequest' method='POST' action='display.php'>";
		echo "   <input type='hidden' name='id' value='" . $id . "' />";
		echo "   <input type='hidden' name='contentType' value='request' />";
		echo "   <input type='hidden' name='mode' value='" . $mode . "' />";
		echo "   <input type='hidden' name='team_id' value='" . $teamId . "' />";
		echo "</form></div>";
		echo "<label for='requestButton'>" . $legend . "</label>";
		echo "<a id='requestButton' class='dialogButton' onclick='javascript:submitForm(\"Frequest\")'>";
		echo $label;
		echo "</a></div>";
	}
	
	public function userIsAuthor() {
		if ($this->authorId == $_SESSION{user_id})
			return true;
		else
			return false;
	}

}
?>