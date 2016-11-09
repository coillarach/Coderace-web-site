// The following variables are created by PHP:
// gameId
// gameName
// gameLatitude
// gameLongitude
// gameZoom

// Declare global variables
var rootDir="http://www.coderace.co.uk/";
var imageDir = "../images/game/";
var map;
var markers = new Array();
var players = new Array();
var marker_count = 0;
var markersSet=false;
var infowindow = new google.maps.InfoWindow({});
var gameTimer = null;
var playersVisible = false;

/* Function which replaces document.getElementById()
 * with $  - Similar to PHP
 */ 
function $(element) {
	return document.getElementById(element);
}

/* Standard Google Maps function for creating the map
 */
function initialise() {
	zeroMap();
	getGameState();
}

function tick(remaining) {
    var hours   = Math.floor(remaining / 3600);
    var minutes = Math.floor((remaining - (hours * 3600)) / 60);
    var seconds = remaining - (hours * 3600) - (minutes * 60);
	
    if (hours   < 10) {hours   = "0"+hours;}
    if (minutes < 10) {minutes = "0"+minutes;}
    if (seconds < 10) {seconds = "0"+seconds;}
    var time    = hours+':'+minutes+':'+seconds;
    $('timer').innerHTML = "Remaining: " + time;
	
	var newTime = remaining - 1;
	if (newTime >= 0)
		gameTimer=setTimeout('tick(' + newTime + ')',1000);
}

function zeroMap() {
	var myLatlng = new google.maps.LatLng(gameLatitude,gameLongitude);
  	var myOptions = {
   		zoom: gameZoom,
   		center: myLatlng,
   		mapTypeId: google.maps.MapTypeId.SATELLITE,
		mapTypeControl: false,
		panControl: false,
		zoomControl: true,
		zoomControlOptions: {
			position: google.maps.ControlPosition.LEFT_CENTER
		},
		scaleControl: false,
		streetViewControl: false,
	}
  	map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	markers = new Array();
	players = new Array();
	markersSet=false;
}

function showClue(m) {
	var outString="";
	outString += "<div>";
	outString += "<img src='../images/clue.png' />";
	outString += "<p id='bubble'>" + markers[m].clue + "</p>";
	outString += "</div>";
	return outString;
}

function showInfo(m) {
	var outString="";
	outString += "<div>";
	outString += "<img src='" + players[m].teamIcon + "' />";
	outString += "<p id='bubble'>" + players[m].username + " (" + players[m].team + ")</p>";
	outString += "</div>";
	return outString;
}

// AJAX call to refresh display
function getGameState() {
	var request=getHTTPObject();
	if (request) {
		request.onreadystatechange = function() {
			updateGameState(request);
//			showResponse(request);
		}
	}
	request.open("GET","monitor_getGameState.php?gameId=" + gameId,true);
	request.send();

	var t=setTimeout('getGameState()',5000);

}

// Handle AJAX response 
function updateGameState(request) {
	if (request.readyState == 4) {
		if (request.status == 200 || request.status == 304) {
			eval("data="+request.responseText);
			var statusString = "<table>";
			var alertString = "<table>";
			var maxScore = -1;
			var second   = 0;
			var taken    = 0;
			var free     = 0;
			var winner   = "";
			var draw     = "";
			
			clearTimeout(gameTimer);
			if (data.gameStatus == 'current') 
				tick(data.remaining);
			for (var m in data.teams) {
				statusString += "<tr><td>";
				if (data.teams[m].name != 'free' && data.teams[m].name != 'total')
					statusString += "<img src='" + imageDir + data.gameId + "/" + data.teams[m].icon + "' />";
				statusString += "</td><td>" + data.teams[m].name + ":</td><td>" + data.teams[m].score + "</td></tr>";

				if (data.teams[m].name == 'free')
					free = data.teams[m].score;
				else if (data.teams[m].name != 'total') {
					taken = taken + data.teams[m].score;
					
					if (data.teams[m].score > maxScore) {
						maxScore = data.teams[m].score;
						winner = data.teams[m].name;
						draw = "";
					}
					else if (data.teams[m].score == maxScore) {
						winner = winner + " and " + data.teams[m].name;
						draw='Y';
					}
				}
			}

			statusString += "</tr></table>";

			$('gameSummary').innerHTML = statusString;

			if (data.gameStatus == 'over') {
				if (draw == "")
					showMessage("Game over - " + winner + " are the winners!");
				else
					showMessage("Game over - " + winner + " draw!");
				zeroMap();
				return;				
			}
			else if (data.gameStatus == 'waiting') {
				showMessage("Game starts in " + data.gameTime);
				zeroMap();
				return;
			}
			else if (data.message.length>0) {
				showMessage(data.message);
				var t=setTimeout('hideMessage()',2000);
			}

			hideMessage();

			if (markers.length > 0) {
				markersSet=true;
			}

			for (var m in data.locations) {
				if (markersSet) {
					// markers are set - update only
					if (data.locations[m].team != "") {
						markers[m].setIcon(imageDir + data.gameId + "/" + data.locations[m].icon);
						if (markers[m].listenerId != -1) {
							google.maps.event.removeListener(markers[m].listenerId);
						}
						markers[m].setTitle("Location claimed by " + data.locations[m].player + " for " + data.locations[m].team + "!");
						markers[m].setZIndex(1);
					}
					if (data.locations[m].visible == 1) {
						markers[m].setMap(map);
					}
				}
				else {
					// markers are not set - insert into array
					var myLatlng = new google.maps.LatLng(data.locations[m].latitude,data.locations[m].longitude);
  					markers[m] = new google.maps.Marker({
						index: m,
						zIndex: 2,
      					position: myLatlng,
						title: "Click for the clue",
						anchor: new google.maps.Point(30, 30),
						id: data.locations[m].id,
						clue: data.locations[m].clue,
						listenerId: -1
  					});
					if (data.locations[m].visible==1) {
						markers[m].setMap(map);
					}
					if (data.locations[m].team == "") {
						markers[m].listenerId = google.maps.event.addListener(markers[m], 'click', function() {
							infowindow.content = showClue(this.index);
							infowindow.open(map,markers[this.index]);
						});
						var image = new google.maps.MarkerImage(imageDir + data.gameId + "/free.svg",
								// This marker is 66 pixels wide by 66 pixels tall.
								new google.maps.Size(66, 66),
								// The origin for this image is 0,0.
								new google.maps.Point(0,0),
								// The anchor for this image is the center at 33,33.
								// For some reason, one parameter must always be zero 
								new google.maps.Point(0, 33));
						markers[m].setIcon(image);
					}
					else {
						var image = new google.maps.MarkerImage(imageDir + data.gameId + "/" + data.locations[m].icon,
								// This marker is 24 pixels wide by 24 pixels tall.
								new google.maps.Size(24, 24),
								// The origin for this image is 0,0.
								new google.maps.Point(0,0),
								// The anchor for this image is the center at 12,12.
								new google.maps.Point(0, 12));
						markers[m].setIcon(image);
						markers[m].setTitle("Location claimed by " + data.locations[m].player + " for " + data.locations[m].team + "!");
						markers[m].setZIndex(1);
					}
				}
			}

			// Process player data

			for (var p in data.players) {
				if (markersSet) {
					// markers are set - update only
					players[p].setZIndex(1);
					if (data.players[p].latitude != 0) {
						var myLatlng = new google.maps.LatLng(data.players[p].latitude/1000000,data.players[p].longitude/1000000);
						players[p].setPosition(myLatlng);
						players[p].setMap(map);
					}
				}
				else {
					// insert into array
					var myLatlng;
					if (data.players[p].latitude != 0) {
						myLatlng = new google.maps.LatLng(data.players[p].latitude/1000000,data.players[p].longitude/1000000);
					}
					else {
						myLatlng = new google.maps.LatLng(gameLatitude,gameLongitude);
					}
					players[p] = new google.maps.Marker({
						id: data.players[p].id,
						index: p,
						position: myLatlng,
						username: data.players[p].username,
						team: data.players[p].team,
						teamIcon: imageDir + data.gameId + "/" + data.players[p].icon,
						icon: imageDir + data.gameId + "/player.svg"
  					});
					
					if (data.players[p].latitude > 0) {
						players[p].setMap(map);
						players[p].listenerId = google.maps.event.addListener(players[p], 'click', function() {
							infowindow.content = showInfo(this.index);
							infowindow.open(map,players[this.index]);
						});

					}
				}
					
				players[p].setVisible($('playerCB').checked);
			}
			
			for (var m in data.events) {
				switch(data.events[m].message) {
					case 'Wrong code!' :			imgsrc='../images/wrong_code.svg';
													break;
					case 'No code entered!':		imgsrc='../images/no_code.svg';
													break;
					case 'Marker already taken!':	imgsrc='../images/taken.svg';
													break;
					case 'Marker claimed!'		:	imgsrc='../images/right_code.svg';
					
				}
				
				// Find marker in array
				j = 0;
				while (markers[j].id != data.events[m].location)
					j++;

				// Find player in array
				k = 0;
				while (players[k].id != data.events[m].playerId)
					k++;
				alertString += "<tr><td><a href='javascript:";
				alertString += "showEvent(" + k + ", " + j + ", \"" + markers[j].clue + "\", \"" + data.events[m].message + "\", \"";
				alertString += data.events[m].detail + "\")' >"; 
				alertString += "<img src='"  + imgsrc + "' /></a>";
				alertString += "</td><td>" + data.events[m].player;
				alertString += "</td></tr>";
			}

			alertString += "</table>";
			$('alertDiv').innerHTML = alertString;
		}
	}
}

function togglePlayers() {
	for (var p in players)
		players[p].setVisible($('playerCB').checked);
}

function showEvent(p, l, clue, message, detail) {
	var outString="";
	outString += "<div>";
	outString += "<img src='" + players[p].teamIcon + "' />";
	outString += "<p id='bubble'>" + clue + "<br />";
	outString += players[p].username + " said: " + detail + "<br />";
	outString += message + "</p>";
	outString += "</div>";
	infowindow.content = outString;
	infowindow.open(map,markers[l]);
}

function showMessage(messageString) {
	if (window.innerHeight) {
		$('message').style.height=window.innerHeight/2;
		$('message').style.top=window.innerHeight/2;
	}
	else {
		$('message').style.height=document.body.offsetHeight/2; 
		$('message').style.top=document.body.offsetHeight/2; 
	}
	$('message').innerHTML=messageString;
	$('message').style.visibility='visible';
	$('messageBackground').style.visibility='visible';
}

function hideMessage() {
	$('message').style.visibility='hidden';
	$('messageBackground').style.visibility='hidden';
}
//
// Debug AJAX response 
function showResponse(request) {
// (request.readyState);
	if (request.readyState == 4) {
		if (request.status == 200 || request.status == 304) {
			alert(request.responseText);
		}

	}
// document.write(request.responseText);
}