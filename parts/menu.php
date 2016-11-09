<?php
	require_once('menu_item.php');

	echo "<br/>";
	$menu = recursivesubtree( 0, $a = array(), 0 );
	foreach( $menu as $item ) {
		$item->display();
	}
	
	echo "<iframe id='socialLinks' scrolling='no'></iframe>";
	
	function recursivesubtree( $rootID, $a, $level ) {
		$childcountquery = "(SELECT COUNT(*) FROM menu WHERE parent_id=m.id) AS children";
		$query  = "SELECT m.id, m.label, m.action, m.parent_id, $childcountquery, ";
		$query .= "$level as level, m.content_id, m.content_type, m.public_flag, ";
		$query .= "m.display_if_set, m.display_if_unset ";
		$query .= "FROM menu m " ;
		$query .= "WHERE parent_id=$rootID ORDER BY m.sequence";
		$result = mysql_query( $query );
		if (!$result) {
			$message = 'Invalid query: ' . mysql_error() . "\n";
			die($message);
		}

		while( $row = mysql_fetch_array( $result, MYSQL_ASSOC )) {
			// Skip any menu items where the appropriate SESSION variables are not set/unset
			if ($row{display_if_set} != "" && !isset($_SESSION[$row{display_if_set}]))
				continue;
			if ($row{display_if_unset} != "" && isset($_SESSION[$row{display_if_unset}]))
				continue;
				
			if ($row{public_flag} == "Y" || isset($_SESSION{user_id})) {
				$menuitem = new MenuItem($row{id}, $row{label}, $row{parent_id}, $row{children}, $row{level}, $row{action}, $row{content_id}, $row{content_type}, $row{public_flag});
				$a[] = $menuitem;
				if( $menuitem->children > 0 ) $a = recursivesubtree( $menuitem->id, $a, $level+1 );    // down before right
			}
		}
		return $a;
	}
?>
