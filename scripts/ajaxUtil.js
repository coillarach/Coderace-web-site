// AJAX utility functions

// Keith, 2007, p.50
function getHTTPObject() {
	var xhr = false;
	if (window.XMLHttpRequest) {
		xhr = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		try {
			xhr = new ActiveXObject("Msxml2.XMLHTTP");
		} catch(e) {
			try {
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			} catch(e) {
				xhr = false;
			}
		}
	}
	return xhr;
}

function spinner(ajaxDiv) {

	var spinDiv = document.createElement("div");
	spinDiv.id = ajaxDiv.id + "_spinner";
	spinDiv.innerHTML="<img src='images/ajax-loader.gif'/>";
	spinDiv.style.zIndex=100;
	spinDiv.style.position = 'relative';
	spinDiv.style.left = ajaxDiv.clientWidth/2-25 + "px";
	spinDiv.style.top = ajaxDiv.clientHeight/2-25 + "px";

	ajaxDiv.innerHTML = "";
	ajaxDiv.appendChild(spinDiv);

	return spinDiv.id;
}

function noSpinner(ajaxDiv) {

	var d=ajaxDiv.id + "_spinner";
	var spinDiv= document.getElementById(d);
	
	ajaxDiv.removeChild(spinDiv);

	return true;
}


/* REFERENCES
 *
 * Keith, J. (2007) BUlletproof AJAX. New Riders
 * 
*/
