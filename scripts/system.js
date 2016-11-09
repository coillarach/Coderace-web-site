// System state
var state = {
	'user_id': 0
}
var highlight = '#ffffaa';

var messages = {
	'1': 'Login details were not recognised',
	'2': 'Duplicate details found: please try again',
	'3': 'This request has been accepted. Please withdraw from the team on your profile page',
	'4': 'The team still has members. Please remove them all individually before deleting the team itself',
	'5': 'You cannot delete your account while you are a team leader. Please remove the team first.',
	'6': 'Sorry - you cannot delete the home page',
	'7': 'Your password has been sent to your email address',
	'8': 'Your session has been timed out for inactivity. Please log in again.',
	'9': 'This question has already been answered and cannot be deleted',
	'10': 'The email address you supplied was not recognised'
}

// Actions ################################################################

function doLogin() {
	if (validate_login()) {
		document.forms.Flogin.submit();
	}
	else {
		feedback();
	}
}

function doEdit() {
	document.forms.Fgoedit.submit();
}

function submitForm(fid) {
	F = document.getElementById(fid);
	F.submit();
}

function save_user() {
	var f = document.getElementById('id');

	if (validate_details()) {
		f = document.getElementById('status');
		f.value = 'Registered';
		submitForm('Fedit');
	}
	else {
		feedback();
	}
}

// Validation ##############################################################

function validate_required_field(fid) {
	var f = document.getElementById(fid);
	f.style.backgroundColor = '';
	if (f.value == "" || (f.type == 'checkbox' && f.checked == false)) {
		f.style.backgroundColor = highlight;
		return false;
	}
	return true;
}

function validate_login() {
	var ok = true;
	if (!validate_required_field('login_username')) {ok = false}
	if (!validate_required_field('login_password')) {ok = false}
	if (!ok) {
		setState('message', getState('message') + "Username and password are both required");
		return ok;
	}

	return ok;
}


function validate_regexp(fid, regexpString) {
	var pattern = new RegExp(regexpString);
	var f = document.getElementById(fid);
	f.style.backgroundColor = '';

	if (f.value == "") {
		return true;
	}
	else if (pattern.test(f.value)) {
		return true;
	}
	else {
		f.style.backgroundColor = highlight;
		return false;
	}
}
function validate_webAddress(fid) {
	var retval = validate_regexp(fid, "^(http|https|ftp)\://[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(:[a-zA-Z0-9]*)?/?([a-zA-Z0-9\-\._\?\,\'/\\\+&amp;%\$#\=~])*$");
	return retval;
}

function validate_email(fid) {
	var retval = validate_regexp(fid, "^([0-9a-zA-Z]([-.\w]*[0-9a-zA-Z])*@([0-9a-zA-Z]([-\w]*[0-9a-zA-Z])*\.)+)[a-zA-Z]{2,9}$");
	return retval;
}

function validate_details() {
	var p = document.getElementById('password');
	var a = document.getElementById('agree');
	var ok = true;
	if (!validate_required_field('first_name')) {ok = false}
	if (!validate_required_field('last_name')) {ok = false}
	if (!validate_required_field('email')) {ok = false}
	if (!validate_required_field('username')) {ok = false}
	if (p && p.value != p.defaultValue) {
		if (!validate_required_field('password')) {ok = false}
		if (!validate_required_field('check_password')) {ok = false}
	}

	if (!ok) {
		setState('message', getState('message') + "The highlighted fields are required");
	}
	else if (!validate_email('email')) {
		setState('message', "Email address is incorrectly formatted. Please try again");
		return false;
	}
	else if (a)
		if (!validate_required_field('agree')) {
			ok = false;
			setState('message', getState('message') + "Please read the player responsibilities page and agree by checking the last checkbox.");
		}
	else if (p && !validate_password('password', 'check_password')) {
		setState('message', "Passwords do not match. Please try again");
		return false;
	}

	return ok;
}

function validate_password(f1,f2) {
	var p1 = document.getElementById(f1);
	var p2 = document.getElementById(f2);
	p1.style.backgroundColor = '';
	p2.style.backgroundColor = '';
	if (p1.value != p2.value) {
		p1.style.backgroundColor = highlight;
		p2.style.backgroundColor = highlight;
		setState('message', getState('message') + "Passwords do not match<br/>");
		return false;
	}

	return true;
}

// General ######################################################################

function home() {
	top.location.href='index.php';
}

function setState(key, val) {
	state[key] = val;
}

function getState(key) {
	if (state[key]) {
		return state[key];
	}
	else {
		return "";
	}
}

function deleteState(key) {
	var tempArray = new Array();
	for (v in state) {
		if (v != key) {
			tempArray[v] = state[v];
		}
	}
	state = tempArray;
}

function setMessage(key) {
	for (v in messages) {
		if (v == key) {
			setState('message', messages[v]);
			break;
		}
	}
}

function initialise(contentType, contentId, authorId) {
	resizeBanner();
	// Do class-specific initialisation - function in separate script file
	eval(contentType + "Initialise(" + contentId + ", " + authorId + ")");
	setFormValues(getState('queryString'));
	feedback();
	
	// Load social networking links
	document.getElementById('socialLinks').src = "social.html";
}

function feedback() {
	if (state.message) {
		$lines = state.message.length / 20;
		$width = 200 + ($lines * 32);
		$height = 80 + $lines;
		OKDialog(state.message, $height, $width);
		deleteState('message');
	}
}

function popup(string) {
	rules=window.open('','Rules');
	rules.document.write("<html><head><title>Coderace rules</title>");
	rules.document.write("<link type='stylesheet' src='http://coderace.co.uk/css/coderace_layout.css' />");
	rules.document.write("</head><body>");
	rules.document.write(string.replace(/\+/g, ' '));
	rules.document.write("</body></html>");
}

function resizeBanner() {
	var winW = 630, winH = 460;
	var banner = document.getElementById('banner');
	
	if (document.body && document.body.offsetWidth) {
		winW = document.body.offsetWidth;
		winH = document.body.offsetHeight;
	}
	else if (document.compatMode=='CSS1Compat' &&
		document.documentElement &&
		document.documentElement.offsetWidth ) {
			winW = document.documentElement.offsetWidth;
			winH = document.documentElement.offsetHeight;
	}
	else if (window.innerWidth && window.innerHeight) {
		winW = window.innerWidth;
		winH = window.innerHeight;
	}
	
	var menuFields = document.getElementsByName('windowHeight');
	for (var fname in menuFields)
		fname.value = winH;
	menuFields = document.getElementsByName('windowWidth');
	for (var fname in menuFields)
		fname.value = winW;

	var banner_width = winW * 0.8;
	banner.style.width = banner_width + 'px';
}

// http://www.htmlgoodies.com/beyond/javascript/article.php/11877_3755006_2/How-to-Use-a-JavaScript-Query-String-Parser.htm
function queryStringToObject(q)
{
	/* parse the query */
	var x = q.replace(/;/g, '&').split('&'), i, name, t;
	/* q changes from string version of query to object */
	for (q={}, i=0; i<x.length; i++)
	{
		t = x[i].split('=', 2);
		name = unescape(t[0]);
		if (!q[name])
			q[name] = [];
		if (t.length > 1) {
			// Replace '+' in advance of unescape() so that spaces can be reintroduced later
			t[1] = t[1].replace(/\+/g,' ');
			q[name][q[name].length] = unescape(t[1]);
		}
		/* next two lines are nonstandard */
		else
			q[name][q[name].length] = true;
	}
	return q;
}

// Assumes that form fields have id=name
function setFormValues(string) {
	if (!string)
		return;
	obj = queryStringToObject(string);
	for (var fname in obj) {
		field = document.getElementById(fname);
		field.value = obj[fname];
	}
	deleteState('queryString');
}

function showStatDiv(did) {
	statDivs = document.getElementsByClassName('statDivVisible');
	for (var i=0; i < statDivs.length; i++)
		statDivs[i].className = 'statDiv';
	if (did)
		document.getElementById(did).className = 'statDivVisible';
}

// Dialogue boxes #################################

function formConfirm(msg,action,noLabel,yesLabel,dheight,dwidth) {
	// Dark layer
	var overlay = document.createElement("div");
	overlay.id = "dialogOverlay";
	overlay.style.zIndex=100;
	overlay.style.height=document.documentElement.scrollHeight+'px';

	// Transparent placeholder to position dialog
	var reference = document.createElement("div");
	reference.id = "dialogPosition";
	reference.style.zIndex=100;
	reference.style.height=document.documentElement.scrollHeight+'px';

	// Dialog
	var dialog = document.createElement("div");
	dialog.id = "dialog";
	dialog.style.zIndex=103;
	dialog.style.height=dheight+'px';
	dialog.style.width=dwidth+'px';

    var content = "<div id='container'> ";
	content += "<table id='dialogTable'><tr><td id='dialogMessage'>Default message</td></tr>";
	content += "<td id='dialogButtons'>";
	content += "<div class='linkButton'><a id='dialogNoButton' class='dialogButton' onclick='dialogClose()'>Cancel</a></div>";
	content += "<div class='linkButton'><a id='dialogYesButton' class='dialogButton' onclick='alert(\"Default message\")'>Confirm</a></div>";
	content += "</td></tr></table>";
	content += "</div>";
	dialog.innerHTML = content;

	document.getElementsByTagName("body")[0].appendChild(overlay);
	document.getElementsByTagName("body")[0].appendChild(reference);
	document.getElementById('dialogPosition').appendChild(dialog);

	document.getElementById('container').style.height=dheight+'px';
	document.getElementById('dialogTable').style.height=dheight+'px';
	document.getElementById('container').style.width=dwidth+'px';
	document.getElementById('dialogTable').style.width=dwidth+'px';
	document.getElementById('dialogMessage').innerHTML = msg;
	document.getElementById('dialogNoButton').innerHTML=noLabel;
	document.getElementById('dialogYesButton').innerHTML=yesLabel;

	document.getElementById('dialogYesButton').setAttribute("onclick", "dialogClose(\""+action+"\")");

	return true;
}

function dialogClose(onClickText) {

	var overlay= document.getElementById("dialogOverlay");
	var dialog= document.getElementById("dialog");
	var reference = document.getElementById("dialogPosition");
	
	reference.removeChild(dialog);
	
	document.getElementsByTagName("body")[0].removeChild(overlay);
	document.getElementsByTagName("body")[0].removeChild(reference);

	if (onClickText) {
		eval(onClickText);
	}
	return true;
}

function OKDialog(msg,dheight,dwidth) {
	// Dark layer
	var overlay = document.createElement("div");
	overlay.id = "dialogOverlay";
	overlay.style.zIndex=100;
	overlay.style.height=document.documentElement.scrollHeight+'px';

	// Transparent placeholder to position dialog
	var reference = document.createElement("div");
	reference.id = "dialogPosition";
	reference.style.zIndex=100;
	reference.style.height=document.documentElement.scrollHeight+'px';

	// Dialog
	var dialog = document.createElement("div");
	dialog.id = "dialog";
	dialog.style.zIndex=101;
	dialog.style.height=dheight+'px';
	dialog.style.width=dwidth+'px';

    var content = "<div id='container'> ";
	content += "<table id='dialogTable'><tr><td id='dialogMessage'>Default message</td></tr>";
	content += "<td id='dialogButtons'>";
	content += "<div class='linkButton'><a id='dialogNoButton'  class='dialogButton' onclick='dialogClose()'>OK</a></div>";
	content += "</td></tr></table>";
	content += "</div>";
	dialog.innerHTML = content;

	document.getElementsByTagName("body")[0].appendChild(overlay);
	document.getElementsByTagName("body")[0].appendChild(reference);
	document.getElementById('dialogPosition').appendChild(dialog);

	document.getElementById('container').style.height=dheight+'px';
	document.getElementById('dialogTable').style.height=dheight+'px';
	document.getElementById('container').style.width=dwidth;
	document.getElementById('dialogTable').style.width=dwidth;
	document.getElementById('dialogMessage').innerHTML = msg;

	var b=document.getElementById('dialogNoButton');
	b.focus();

	return true;
}

function imageDialog(fname, dheight, dwidth) {
	// Dark layer
	var overlay = document.createElement("div");
	overlay.id = "dialogOverlay";
	overlay.style.zIndex=100;
	overlay.style.height=document.documentElement.scrollHeight+'px';

	// Transparent placeholder to position dialog
	var reference = document.createElement("div");
	reference.id = "dialogPosition";
	reference.style.zIndex=100;
	reference.style.height=document.documentElement.scrollHeight+'px';

	// Dialog
	var dialog = document.createElement("div");
	dialog.id = "dialog";
	dialog.style.zIndex=102;
	dialog.style.height=dheight+'px';
	dialog.style.width=dwidth+'px';

    var content = "<div id='container' style='background-image:url(\"" + fname + "\");background-repeat:no-repeat;background-position:center;' onclick='dialogClose()'> ";
	dialog.innerHTML = content;

	document.getElementsByTagName("body")[0].appendChild(overlay);
	document.getElementsByTagName("body")[0].appendChild(reference);
	document.getElementById('dialogPosition').appendChild(dialog);

	document.getElementById('container').style.height=dheight+'px';
	document.getElementById('container').style.width=dwidth+'px';

}

function imageUploadDialog(fname, dheight, dwidth) {
	// Dark layer
	var overlay = document.createElement("div");
	overlay.id = "dialogOverlay";
	overlay.style.zIndex=100;
	overlay.style.height=document.documentElement.scrollHeight+'px';

	// Transparent placeholder to position dialog
	var reference = document.createElement("div");
	reference.id = "dialogPosition";
	reference.style.zIndex=100;
	reference.style.height=document.documentElement.scrollHeight+'px';

	// Dialog
	var dialog = document.createElement("div");
	dialog.id = "dialog";
	dialog.style.zIndex=102;
	dialog.style.height=dheight+'px';
	dialog.style.width=dwidth+'px';

    var content = "<div id='container' style='background-image:url(\"" + fname + "\");background-repeat:no-repeat;background-position:center;'> ";
	content += "<table id='dialogTable'><tr><td id='dialogText'>";
	content += "<form id=\"uploadForm\" action=\"upload.php\" method=\"post\" enctype=\"multipart/form-data\" target=\"profileImage\" >";
	content += "<label for=\"file\">Filename:</label>";
	content += "<div id='fname'>No file chosen</div>";
	content += "<input type=\"file\" name=\"file\" id=\"file\"  onChange='displayFileName(this, \"fname\")'/>"; 
	content += "<br />";
	content += "</form>";
	content += "</td></tr>";
	content += "<td id='dialogButtons'>";
	content += "<div class='linkButton'><a class='dialogButton' onclick='javascript:browse(\"file\")'>Browse...</a></div>&nbsp;&nbsp;&nbsp;";
	content += "<div class='linkButton'><a class='dialogButton' onclick='javascript:doUpload()'>Upload</a></div>&nbsp;&nbsp;&nbsp;";
	content += "<div class='linkButton'><a class='dialogButton' id='imageDialogClose' onclick='dialogClose()'>Cancel</a></div>";
	content += "</td></tr></table></div>";
	dialog.innerHTML = content;

	document.getElementsByTagName("body")[0].appendChild(overlay);
	document.getElementsByTagName("body")[0].appendChild(reference);
	document.getElementById('dialogPosition').appendChild(dialog);

	document.getElementById('container').style.height=dheight+'px';
	document.getElementById('container').style.width=dwidth+'px';

}
// Utility functions for image file uploading
function basename(path) {
	return path.replace(/\\/g,'/').replace( /.*\//, '' );
}
function browse(bid) {
	button = document.getElementById(bid);
	button.click();
}
function displayFileName(button, divid) {
	d = document.getElementById(divid);
	d.innerHTML = basename(button.value);
}

function doUpload() {
	
	var newImage = document.createElement("OPTION"); 
	document.Fedit.image.options.add(newImage);
	document.Fedit.image.options.add(newImage);
	var newFile = document.getElementById("file"); 
	newImage.innerText = basename(newFile.value); 
	newImage.value = basename(newFile.value);
	document.Fedit.image.selectedIndex = document.Fedit.image.options.length - 1;
	
	submitForm('uploadForm');
	dialogClose();
}

function showImageInFrame(image,framename,width,height) {
/* function showimageinframe by Sathallrin. See http://www.webmasterworld.com/forum91/3880.htm */
	var source = "<img style='width:"+width+"px; height:"+height+"px;' src='"+image+"' />";
	var myframe = document.getElementById(framename).contentWindow.document;
	myframe.open();
	myframe.write(source);
	myframe.close();
}

function statusLine($string) {
	window.status = $string;
	return true;
}

function monitor(id) {
	m = window.open('monitor/monitor.php?gameId=' + id);
}

