<?php
// Class definition for a user
class User extends Content
{
	// Constants
	const pwidth = 150;			// width of profile image
	const pheight = 175;		// height of profile image - also needs to be maintained in css rule for profileImage

    // property declaration
	public $contentType = 'user'; 
    public $firstName = '';
	public $lastName = '';
	public $username = '';
	public $password = '';
	public $email = '';
	public $comment = '';
	public $image = 'default.png';
	public $teamId = 'team id';
	public $teamLeader = 0;
	public $team = 'None';
	public $teamIcon = 'default.png';
	public $paymentRef = 'payment ref';
	public $status = 'New';
	public $mailFlag2012 = '';
	public $mailFlagFuture = '';

    // method declaration
	public function populate($id) {
		$query  = "select u.id, ";
		$query .= "       u.author_id, ";
		$query .= "       u.visible, ";
		$query .= "       u.first_name, ";
		$query .= "       u.last_name, ";
		$query .= "       u.username, ";
		$query .= "       u.email, ";
		$query .= "       u.comment, ";
		$query .= "       ifnull(u.image,'default.png') as photo, ";
		$query .= "       u.team_id, ";
		$query .= "       t.name, ";
		$query .= "       ifnull(t.image,'default.png') as icon, ";
		$query .= "       t.author_id as team_leader, ";
		$query .= "       u.payment_ref, ";
		$query .= "       u.status, ";
		$query .= "       u.create_date, ";
		$query .= "       u.mail_flag_2012, ";
		$query .= "       u.mail_flag_future ";
		$query .= "from   user u left join team t ";
		$query .= "       on u.team_id = t.id ";
		$query .= "where  u.id = " . $id;
		$result = mysql_query($query) or die('Invalid query: ' . mysql_error() . "\n");
		
		$content = mysql_fetch_array($result, MYSQL_ASSOC);
		$this->id = $content{id};
		$this->authorId = $content{author_id};
		$this->visible = $content{visible};
		$this->firstName = $content{first_name};
		$this->lastName = $content{last_name};
		$this->username = $content{username};
		$this->email = $content{email};
		$this->comment = $content{comment};
		$this->image = $content{photo};
		$this->teamId = $content{team_id};
		$this->team = $content{name};
		$this->teamLeader = $content{team_leader};
		$this->teamIcon = $content{icon};
		$this->paymentRef = $content{payment_ref};
		$this->status = $content{status};
		$this->createDate = $content{create_date};
		$this->mailFlag2012 = $content{mail_flag_2012};
		$this->mailFlagFuture = $content{mail_flag_future};
	}

    public function display() {
        echo "<div id='main'>";
		if ($_SESSION{user_id} == $this->authorId) 
			authorControls($this->contentType, $this->id, $this->id, $this->authorId, $this->visible, 'This will delete your profile and close your account', 1, 'content');
		echo "<h1>";
		echo $this->username;
		echo "</h1>";
		echo "<h3>";
		echo $this->firstName . " " . $this->lastName;
		echo "</h3>";
		echo "<div id='profileImage'>";

		$scale = fitImageToDiv($this->getImage(), $this::pheight, $this::pwidth);
		echo "<img id='main_image' src='" . $this->getImage() . "' " . $scale . "/>";

		echo "</div>";
		if ($this->team) {
			echo "<div class='userTeam'>";
			echo "<div class='hidden'>";
			echo "<form name='Fmenu' id='Fmenu' method='POST' action='display.php'>";
			echo "   <input type='hidden' name='id' value='" . $this->teamId . "' />";
			echo "   <input type='hidden' name='contentType' value='team' />";
			echo "   <input type='hidden' name='mode' value='display' />";
			echo "</form></div>";
			echo "<a href='javascript:submitForm(\"Fmenu\")'>";
			$tscale = fitImageToDiv($this->image, $this::pheight, $this::pwidth);
			echo "<div class='userTeamImage'>";
			echo "<img src='images/user/" . $this->teamLeader . "/" . $this->teamIcon . "' " . $tscale . "/>";
			echo "</div></a>";
			echo "<div class='userTeamName'>";
			echo $this->team;
			echo "</div></div>";
		}
		echo "<div class='display_text'>";
		echo $this->comment;
		echo "</div>";
		echo "</div>";
    }

	public function displayTitle() {
		echo $this->username;
	}
	
	// Assumes that for a new item, id is set to 0
	public function edit() {
		if ($this->id == -1) {				// This is a password reset request
			echo "<div id='main'>";
			echo "<h1>Password reset</h1><br />";
			echo "<form name='password' action='passwordReset.php' method='post'>";
			echo "Please enter the email address you used to register.<br />";
			echo "<input type='text' name='email' size='50'/><br />";
			echo "<input type='submit' value='Reset password' />";
			echo "</form></div>";
			return;
		}
		
		$content = str_replace('"','&quot;',str_replace("'","&apos;", $this->comment));
		echo "<div id='main'>";
		echo "<fieldset><legend>Your details</legend>";
		echo "<form id='Fedit' name='Fedit' method='POST' action='save.php'>";
		echo "   <input type='hidden' id = 'tableName' name='tableName' value='user' />";
		echo "   <input type='hidden' id = 'status' name='status' value='" . $this->status . "' />";
		echo "   <input type='hidden' id = 'id' name='id' value='" . $this->id . "' />";

		echo "<iframe id='profileImage' name='profileImage' scrolling='no'></iframe>";

		echo "   <div class='editfield'>";
		echo "   	<label for='first_name'>First name:</label>";
		echo "   	<input type='text' name='first_name' id='first_name' value='" . $this->firstName . "'  />";
		echo "   </div><div class='editfield'>";
		echo "   	<label for='last_name'>Last name:</label>";
		echo "   	<input type='text' name='last_name' id='last_name' value='" . $this->lastName . "'  />";
		echo "   </div><div class='editfield'>";
		echo "   	<label for='email'>Email:</label>";
		echo "   	<input type='text' name='email' id='email' value='" . $this->email . "' />";
		echo "   </div><div class='editfield'>";
		echo "   	<label for='email'>Username:</label>";
		echo "   	<input type='text' name='username' id='username' value='" . $this->username . "' />";
		echo "   </div>";
		if (isset($_SESSION{user_id})) {
			echo "   <div class='editfield'>";
			echo "   	<label for='password'>Password:</label>";
			echo "   	<input type='password' name='password' id='password' value='" . $this->password . "' />";
			echo "   </div><div class='editfield'>";
			echo "   	<label for='check_password'>Verify:</label>";
			echo "   	<input type='password' name='check_password' id='check_password' value='' />";
			echo "   </div>";
		}
		echo "      <br />";
		echo "   <div class='editfield'>";
		echo "   	<label for='comment'>Comment:</label>";
		echo "   	<textarea class='editBox' name='comment' id='comment' rows='10' cols='80'>" . $content . "</textarea>";
		echo "   </div><div class='editfield'>";
		echo "   	<label for='image'>Photo:</label>";
		echo "		<select name='image' id='image' onChange='updateProfileImage(" . $_SESSION{user_id} . ", this)'>";
		echo "		<option value=''>Select image file</option>";
		if ($_SESSION{user_id}) {
			foreach (list_directory("images/user/" . $_SESSION{user_id} . "/") as $file) {
				echo "		<option value='" . $file . "' ";
				if ($file == $this->image)
					echo "selected='selected'";
				echo ">" . $file . "</option>";
			}
		}
		echo "		</select>";
		echo "		<input type='button' id='imgUpload' name='imgUpload' value='Upload image' onClick='imageUploadDialog(state.message, 80, 300)' />";
		echo "   </div><div class='noneditfield'>";
		echo "		<label>Status:</label>";
		echo       $this->status;
		echo "   </div><div class='noneditfield'>";
		echo "      <label>Team:</label>";
		echo       $this->team;
		echo "   </div>";
		echo "	 <div class='fieldGroup'><div class='editfield'>";
		echo "   	<label for='mail_flag_2012'>Event news:</label>";
		echo "   	<input type='hidden' name='mail_flag_2012' id='mail_flag_2012_default' value='' />"; // sent if checkbox is unchecked
		echo "   	<input type='checkbox' name='mail_flag_2012' id='mail_flag_2012' value='Y' ";
		if ($this->mailFlag2012 == 'Y')
			echo "checked='checked' ";
		echo "/>";
		echo "		<div class='helptext'>Check to receive email news about the 2012 event</div>";
		echo "   </div><div class='editfield'>";
		echo "   	<label for='mail_flag_future'>Future news:</label>";
		echo "   	<input type='hidden' name='mail_flag_future' id='mail_flag_future_default' value='' />"; // sent if checkbox is unchecked
		echo "   	<input type='checkbox' name='mail_flag_future' id='mail_flag_future' value='Y' ";
		if ($this->mailFlagFuture == 'Y')
			echo "checked='checked' ";
		echo "/>";
		echo "		<div class='helptext'>Check to receive email news about future Coderace events</div>";
		echo "   </div></div>";
		if ($this->id == 0) {
			echo "	 <div class='agreement'>";
			echo "   	<label for='agree'>Agree:</label>";
			echo "   	<input type='hidden' name='agree' id='agree_default' value='' />"; // sent if checkbox is unchecked
			echo "   	<input type='checkbox' name='agree' id='agree' value='Y' />";
			$rules = new Document();
			$rules->populate(6);
			echo "		<div class='helptext'>Check to accept <a href='rules.html' target='_blank'>player responsibilities</a></div>";
			echo "   </div>";
		}
		echo "	 <div class='formButtons'>";
		echo "   	<input type='button' value='Submit' onclick='save_user()'>";
		echo "   	<input type='button' value='Cancel' onClick='submitForm(\"Fcancel\")'>";
		echo "	 </div>";
		echo "</form></fieldset></div>";
		echo "<form id='Fcancel' name='Fcancel' method='POST' action='display.php'>";
		if ($this->id > 0) {
			echo "   <input type='hidden' name='id' value='" . $this->id . "' />";
			echo "   <input type='hidden' name='contentType' value='user' />";
		}
		else {
			echo "   <input type='hidden' name='id' value='1' />";
			echo "   <input type='hidden' name='contentType' value='document' />";
		}
		echo "   <input type='hidden' name='mode' value='display' />";
		echo "</form>";
	}
	
	public function initialise($id, $img) {
		// Generate a new password
		$password = $this->generatePassword(8,4);
		$hash = sha1($password);
		
		// Update author id and password
		$query = "update user set author_id = " . $id . ", status = 'Registered', password='" . $hash . "' where id = " . $id;
		$result = mysql_query($query) or die($_SERVER['PHP_SELF'] . ' Invalid query: ' . mysql_error() . "\n<br>" . $query);
		
		// Send notification of password
		$this->notify('registered',$password,1);
		
		// Create directory for images
		$dir_path = "./images/user/" . $id;
		if (!mkdir($dir_path, 0755, true)) {
			die('Failed to create folder, ' . $dir_path);
		}
		// If file was uploaded, move it
		if ($img)
			rename('images/user/' . $img, 'images/user/' . $id . '/' . $img);

		return '7';
	}
	
	public function delete() {
		$query  = "select id ";
		$query .= "from   team ";
		$query .= "where  author_id = " . $this->id;
		$result = mysql_query($query) or die('Invalid query: ' . mysql_error() . "\n");

		if (mysql_num_rows($result) > 0)
			return 5;

		// Remove image directory
		foreach (list_directory("images/user/" . $this->id . "/") as $file) {
			unlink("images/user/" . $this->id . "/" . $file);
		}
		rmdir("images/user/" . $this->id . "/");
		
		// Destroy session
		$_SESSION = array();
		session_destroy();
		setcookie('PHPSESSID','',time()-3600,'/','',0);

		$query = "delete from user where id = " . $this->id;
		$result = mysql_query($query) or die($_SERVER['PHP_SELF'] . ' Invalid query: ' . mysql_errno() . ': ' . mysql_error() . "\n<br>" . $query);
		
		return 0;
	}
	
	public function listQuery() {
		$query  = "select u.id, u.author_id, u.visible, u.username, ";
		$query .= "       t.name as team, u.image as icon,'default.png' as default_icon ";
		$query .= "from user u left join team t on u.team_id = t.id";
		return $query;
	}
	
	public function listNewControl() {
		// New users created by registration function
	}
	
	public function getImage() {
		if ($this->image)
			return "images/user/" . $this->id . "/" . $this->image;
		else
			return "images/user/default.png";
	}
	
	public function notify($action, $string, $from) {
		$sender = new User();
		$sender->populate($from);
		
		switch ($action) {
			case 'registered':
				$subject = 'Coderace: Registration confirmation';
				$message = 'Thank you for registering on the Coderace site. Your password is: ' . $string;
				break;
			case 'request':
				$subject = 'Coderace: Someone wants to join your team';
				$message = 'You have received a join request. Please log in to the Coderace site and either accept or reject it.';
				break;
			case 'accept':
				$subject = 'Coderace: Join request accepted';
				$message = 'Your request to join "' . $string . '" has been accepted.  Welcome to the team!';
				break;
			case 'reject':
				$subject = 'Coderace: Join request rejected';
				$message = 'Your request to join "' . $string . '" was not accepted. There may be many different reasons for this - please try another!';
				break;
			case 'left':
				$subject = 'Coderace: One of your team members has left';
				$message = $sender->first_name . ' ' . $sender->last_name . ' has decided to leave the team. You may need to find a replacement.';
				break;
			case 'removed':
				$subject = 'Coderace: You have been removed from your team';
				$message = 'The team leader removed you from "' . $string . '". If you were not expecting this, please get in touch to find out why.';
				break;
			case 'reset':
				$subject = 'Coderace: Password reset';
				$message = 'Your new password is ' . $string;
				break;
		}
		$headers = "From: " . $sender->username . " <" . $sender->email. ">";
		mail($this->email,$subject,$message,$headers);
	}
	
	 
	// http://www.webtoolkit.info/php-random-password-generator.html
	private function generatePassword($length=9, $strength=0) {
		$vowels = 'aeuy';
		$consonants = 'bdghjmnpqrstvz';
		if ($strength & 1) {
			$consonants .= 'BDGHJLMNPQRSTVWXZ';
		}
		if ($strength & 2) {
			$vowels .= "AEUY";
		}
		if ($strength & 4) {
			$consonants .= '23456789';
		}
		if ($strength & 8) {
			$consonants .= '@#$%';
		}
 
		$password = '';
		$alt = time() % 2;
		for ($i = 0; $i < $length; $i++) {
			if ($alt == 1) {
				$password .= $consonants[(rand() % strlen($consonants))];
				$alt = 0;
			} else {
				$password .= $vowels[(rand() % strlen($vowels))];
				$alt = 1;
			}
		}
		return $password;
	}
	
	function resetRequest() {
		echo "<div id='main'>";
		echo "<h1>Password reset</h1>";
		echo "<form name='password' action='passwordReset.php' method='post'>";
		echo "Please enter the email address you used to register.";
		echo "<input type='text' name='email' />";
		echo "<input type='submit' value='Reset password' />";
		echo "</form></div>";
	}
	
	function passwordReset($email) {
		// Generate a new password
		$password = $this->generatePassword(8,4);
		$hash = sha1($password);
		
		// Update password
		$query = "update user set password='" . $hash . "' where email = '" . $email . "'";
		$result = mysql_query($query) or die($_SERVER['PHP_SELF'] . ' Invalid query: ' . mysql_error() . "\n<br>" . $query);
		
		if (mysql_affected_rows() == 0)
			return 10;

		// Send notification of password
		$this->email = $email;
		$this->notify('reset',$password,1);

		return 7;
	}

}
?>