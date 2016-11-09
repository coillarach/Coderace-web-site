<?php

/* Contains global settings that should not be visible to users
 * Must contain content type definitions as include files
 */

error_reporting(E_ALL ^ E_NOTICE);

include('connections/site.php');

$query = "select * from site";
$result = mysql_query($query) or die("Could not find site settings: " . mysql_error());
$globals = mysql_fetch_array($result, MYSQL_ASSOC);
$TEMPLATE = $globals{template};
$RULES = $globals{rules_document};

// Superclass
require_once('classes/content.php');

// Content classes ################################### 
require_once('list.php');
require_once('classes/document.php');
require_once('classes/user.php');
require_once('classes/team.php');
require_once('classes/request.php');
require_once('classes/question.php');
require_once('classes/game.php');
require_once('classes/stats.php');

include('functions.php');
?>
