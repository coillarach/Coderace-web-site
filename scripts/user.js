function updateProfileImage(u, s) {
	f = document.getElementById('profileImage');
	f.src = "images/user/" + u + "/" + s.options[s.selectedIndex].value;
}

function userInitialise(id, authorId) {
	// Resize image correctly in iframe in edit mode
	var f = document.getElementById('profileImage');
	if(f.nodeName == 'IFRAME') {
		var img = document.getElementById('image').value;
		if (img)
			showImageInFrame('images/user/' + id + '/' + img,'profileImage',150,175);
		else
			showImageInFrame('images/user/default.png','profileImage',150,175);
	}
}