<?php
echo "<div id='login'>";
echo "<form id='Flogin' name='Flogin' method='post' action='doLogin.php'>";
if(isset($_SESSION{user_id})) {
	echo "Welcome ".$_SESSION{user_first_name};
	echo " | <a class='actionLink' href='doLogout.php'>Logout</a> | ";
}
else {
	echo "Username:&nbsp;";
	echo " <input id='login_username' name='login_username' type='text'>&nbsp;";
	echo "Password:&nbsp;";
	echo "<input id='login_password' name='login_password' type='password'>&nbsp;";
	echo "<input type='submit' class='hidden'>";
	echo "<a class='actionLink' href='#' onClick='doLogin()'>Login</a> | ";
	echo " <a class='actionLink' href='#' onclick='javascript:submitForm(\"Freset\")'>Forgot password</a>&nbsp;";
}

if(!isset($_SESSION{user_id})) {
	echo " | <a class='actionLink' href='#' onclick='javascript:submitForm(\"Fregister\")'>Register</a>&nbsp;";
}
else {
	echo " | <a class='actionLink' href='#' onclick='javascript:submitForm(\"Fprofile\")'>Profile</a>";
}
echo "</form>";
echo "<div class='hidden'>";
echo "<form name='Fregister' id='Fregister' method='POST' action='display.php'>";
echo "   <input type='hidden' name='id' value='0' />";
echo "   <input type='hidden' name='contentType' value='user' />";
echo "   <input type='hidden' name='mode' value='edit' />";
echo "</form></div>";

echo "<div class='hidden'>";
echo "<form name='Fprofile' id='Fprofile' method='POST' action='display.php'>";
echo "   <input type='hidden' name='id' value='" . $_SESSION{user_id} . "' />";
echo "   <input type='hidden' name='contentType' value='user' />";
echo "   <input type='hidden' name='mode' value='display' />";
echo "</form></div>";

echo "<div class='hidden'>";
echo "<form name='Freset' id='Freset' method='POST' action='display.php'>";
echo "   <input type='hidden' name='id' value='-1' />";
echo "   <input type='hidden' name='contentType' value='user' />";
echo "   <input type='hidden' name='mode' value='edit' />";
echo "</form></div>";
echo "</div>";
?>
