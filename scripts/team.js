function updateProfileImage(id, s) {
	var i = document.getElementById('image');
	if (i.value != '')
		showImageInFrame('images/user/' + id + '/' + s.options[s.selectedIndex].value,'profileImage',150,175);
	else
		showImageInFrame('images/user/default.png','profileImage',150,175);
}

function teamInitialise(id, authorId) {
	if (id == 0)
		return;	// No team id - probably in list mode
	// Resize image correctly in iframe in edit mode
	var f = document.getElementById('profileImage');
	if(f.nodeName == 'IFRAME') {
		var img = document.getElementById('image').value;
		if (img)
			showImageInFrame('images/user/' + authorId + '/' + img,'profileImage',150,175);
		else
			showImageInFrame('images/user/default.png','profileImage',150,175);
	}
}

/* Opens speech bubble with member details
 * id = id of member, string = bubble content, 
 * requestId = join request id or zero, 
 * authorId = zero if not owner, user_id otherwise
 */
function teamMemberComment(id, string, requestId, authorId) {
	var controls = '';
	var d = document.getElementById('bubbleBody');

	if (requestId > 0) {
		// Accept
		controls  = "<div class='bubbleControl'>";
		controls += "   <form id='Fgoaccept' name='Fgoaccept' method='POST' action='update.php'>";
		controls += "      <input type='hidden' name='id' value='" + requestId + "' />";
		controls += "      <input type='hidden' name='action' value='accept' />";
		controls += "   </form>";
		controls += "   <div class='iconLink'>";
		controls += "   <a onclick=\"javascript:formConfirm('Accept this request to join the team?', 'submitForm(\\'Fgoaccept\\')','No','Yes','100px','200px')\">";
		controls += "      <img title='Accept' alt='Accept' src='images/accept.png' />";
		controls += "   </a></div>";
		controls += "</div>";
		// Reject
		controls += "<div class='bubbleControl'>";
		controls += "   <form id='Fgoreject' name='Fgoreject' method='POST' action='update.php'>";
		controls += "      <input type='hidden' name='id' value='" + requestId + "' />";
		controls += "      <input type='hidden' name='action' value='reject' />";
		controls += "   </form>";
		controls += "   <div class='iconLink'>";
		controls += "   <a onclick=\"javascript:formConfirm('Reject this request to join the team?', 'submitForm(\\'Fgoreject\\')','No','Yes','100px','200px')\">";
		controls += "      <img title='Reject' alt='Reject' src='images/cancel.png' />";
		controls += "   </a></div>";
		controls += "</div>";
	}
	else if (authorId > 0 && authorId != id) {
		controls  = "<div class='bubbleControl'>";
		controls += "   <form id='Fgoremove' name='Fgoremove' method='POST' action='remove.php'>";
		controls += "      <input type='hidden' name='id' value='" + id + "' />";
		controls += "   </form>";
		controls += "   <div class='iconLink'>";
		controls += "   <a onclick=\"javascript:formConfirm('Remove this member from the team?', 'submitForm(\\'Fgoremove\\')','No','Yes','100px','200px')\">";
		controls += "      <img title='Remove' alt='Remove' src='images/delete.png' />";
		controls += "   </a></div>";
		controls += "</div>";
	}
	
	closeBubble();
	
	var content = "<div class='topleft'><img src='images/topleft.png' /></div>";
	content += "<div class='bottomleft'><img src='images/bottomleft.png' /></div>";
	content += "<div class='topright'><img src='images/topright.png' /></div>";
	content += "<div class='bottomright'><img src='images/bottomright.png' /></div>";
	content += "<div class='bubbleControls'>";
	content += "<div class='bubbleControl'>";
	content += "	<div class='iconLink'>";
	content += "	<a onclick='javascript:closeBubble()'>";
	content += "		<img title='Close' alt='Close' src='images/gray_cross.png' />";
	content += "	</a></div></div>";
	content += controls;
	content += "</div>";
	content += string.replace(/\+/g, ' ');
	d.innerHTML = content;
	
	d.className = 'bubbleBodyVisible';
	
	e = document.getElementById('p'+id);
	e.className = 'speechPointVisible';
}

function closeBubble() {
	var d = document.getElementById('bubbleBody');
	d.className = 'bubbleBody';
	
	var elements = document.getElementsByName('speechPoint');
	for (i=0;i<elements.length;i++) {
		elements[i].className='speechPoint';
	}
}