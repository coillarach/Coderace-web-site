<?php

/* Visit and login statistics by IP address
 */

class Stat {
	public $ip = '';
	public $visits = array();
	public $logins = array();
}

class Visit {
	public $visit_date = '';
	public $host = '';
	public $agent = '';
}
	
class Login {
	public $user;
	public $login_date = '';
	public $logout_date = '';
}

class Stats {


    // property declaration
	public $id = 1;
	public $contentType = 'stats';
	public $authorId = 1;
	public $ips = array();
	
    // method declaration
	
	public function displayTitle() {
		echo "Statistics";
	}

	public function populate($uid) {
		if ($uid > 1)
			$query = "select distinct ip from login where user_id = " . $uid;
		else
			$query = "select ip, count(*) from visit group by ip order by 2 desc";
		$result = mysql_query($query) or die('Could not populate stats: ' . mysql_error() . "\n");
		while($item = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->ips[] = new Stat();
			$i = sizeof($this->ips)-1;
			$this->ips[$i]->ip = $item{ip};

			$subquery 	= "select visit_date, host, agent from visit ";
			$subquery .= "where ip = '" . $this->ips[$i]->ip . "'";
			$subresult = mysql_query($subquery) or die('Could not get visit records: ' . mysql_error() . "\n");
			while($visit = mysql_fetch_array($subresult, MYSQL_ASSOC)) {
				$this->ips[$i]->visits[] = new Visit();
				$this->ips[$i]->visits[sizeof($this->ips[$i]->visits)-1]->visit_date = $visit{visit_date};
				$this->ips[$i]->visits[sizeof($this->ips[$i]->visits)-1]->host = $visit{host};
				$this->ips[$i]->visits[sizeof($this->ips[$i]->visits)-1]->agent = $visit{agent};
			}
			$subquery  = "select user_id, login_date, logout_date from login ";
			$subquery .= "where ip = '" . $this->ips[$i]->ip . "'";
			$subresult = mysql_query($subquery) or die('Could not get login records: ' . mysql_error() . "\n");
			while($login = mysql_fetch_array($subresult, MYSQL_ASSOC)) {
				$this->ips[$i]->logins[] = new Login();
				$this->ips[$i]->logins[sizeof($this->ips[$i]->logins)-1]->user = new User();
				$this->ips[$i]->logins[sizeof($this->ips[$i]->logins)-1]->user->populate($login{user_id});
				$this->ips[$i]->logins[sizeof($this->ips[$i]->logins)-1]->login_date = $login{login_date};
				$this->ips[$i]->logins[sizeof($this->ips[$i]->logins)-1]->logout_date = $login{logout_date};
			}
		}
	}

    public function display() {
		echo "<div id='main'>";
		echo "<div class='info'>";
		if (sizeof($this->ips) == 1)
			echo sizeof($this->ips) . " ip record found";
		else
			echo sizeof($this->ips) . " ip records found";
		echo "</div>";
		
		echo "<table class='statList'><tr>";
		echo "<th>IP address</th>";
		echo "<th>Visits</th>";
		echo "<th>Logins</th>";
		echo "</tr>";
		
		$count = 0;
		foreach ($this->ips as $row) {
			echo "<tr>";
			echo "<td>" . $this->ips[$count]->ip . "</td>";
			echo "<td class='statLink' onclick='showStatDiv(\"visits_" . $this->ips[$count]->ip . "\")'>" . sizeof($this->ips[$count]->visits) . "</td>";
			echo "<td class='statLink' onclick='showStatDiv(\"logins_" . $this->ips[$count]->ip . "\")'>" . sizeof($this->ips[$count]->logins) . "</td>";
			echo "</tr><tr>";
			echo "<td colspan='3'><div class='statDiv' id = 'visits_" . $this->ips[$count]->ip . "'>";
			echo "<div class='controls'><div class='iconLink'>";
			echo "<a class='menu_item' href='#' onclick='javascript:showStatDiv()' >";
			echo "<img title='Close' alt='Close' src='images/gray_cross.png' /></a></div></div>";

			echo "<table class='statDetail'><tr>";
			echo "<td>Date</td>";
			echo "<td>Host</td>";
			echo "<td>Agent</td></tr>";
			foreach ($this->ips[$count]->visits as $visit) {
				echo "<tr><td>" . $visit->visit_date . "</td>";
				echo "<td>" . $visit->host . "</td>";
				echo "<td>" . $visit->agent . "</td></tr>";
			}
			echo "</table></div><div class='statDiv' id='logins_" . $this->ips[$count]->ip . "'>";
			echo "<div class='controls'><div class='iconLink'>";
			echo "<a class='menu_item' href='#' onclick='javascript:showStatDiv()' >";
			echo "<img title='Close' alt='Close' src='images/gray_cross.png' /></a></div></div>";
			echo "<table class='statDetail'><tr>";
			echo "<td>User</td>";
			echo "<td>Login</td>";
			echo "<td>Logout</td></tr>";
			foreach ($this->ips[$count]->logins as $login) {
				echo "<tr><td>" . $login->user->username . "</td>";
				echo "<td>" . $login->login_date . "</td>";
				echo "<td>" . $login->logout_date . "</td></tr>";
			}
			echo "</table></div></td></tr>";

			$count++;
		}
		echo "</table></div>";
	} 
}
?>