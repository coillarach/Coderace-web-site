<?php

error_reporting(E_ALL ^ E_NOTICE);

$db_host  = "localhost";
$username = "root";
$password = "";
$DB_name  = "mondial";

$indent   = "&nbsp;&nbsp;&nbsp;";
$root_dir = "http://localhost/mondialSQL/";
$cgi      = "http://localhost/mondialSQL";
$home_dir = getenv("C_DOCUMENT_ROOT")."/";
$image_dir = "/mondialSQL/images/";

// Connect to MySQL
$link = mysql_connect($db_host, $username, $password) or die('Could not connect: ' . mysql_error());
$db_selected = mysql_select_db($DB_name, $link) or die('Can\'t use ' . $DB_name . ': ' . mysql_error());
?>
