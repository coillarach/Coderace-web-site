<?php
error_reporting(E_ALL ^ E_NOTICE);

$DB_HOST  = "localhost";
$DB_USER = "o110648_cr";
$DB_PASS = "C0d3rac3";
$DB_NAME  = "o110648_coderace";

$indent   = "&nbsp;&nbsp;&nbsp;";
//$DIR_ROOT = "http://localhost/coderace/stage";
//$DIR_CGI      = "http://localhost/coderace/stage";
//$DIR_HOME = getenv("C_DOCUMENT_ROOT")."/";
//$DIR_IMAGE = "/coderace/stage/images/";

// Connect to MySQL
$DB_LINK = mysql_connect($DB_HOST, $DB_USER, $DB_PASS) or die('Could not connect: ' . mysql_error());
$DB_SELECTED = mysql_select_db($DB_NAME, $DB_LINK) or die('Can\'t use database ' . $DB_NAME . ': ' . mysql_error());

?>
