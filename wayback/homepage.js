// Copyright (c)1991-2000 monospace. All Rights Reserved.
// Use of this code without written permission from the copyright owner is expressly prohibited.

ns = (document.layers)? true: false;
ie = false;
if (!ns){
	ie = (document.all)? true: false;
}
mac = (navigator.platform.indexOf('Mac') != -1);

// ----------------------- colorcycle

colorVal = 255; direction = 'down'; color = 'red';
function colorCycle(layer){
	hexColor = hex(colorVal);
	if (color == 'red') document.all[layer].style.color =  'ff' + hexColor + hexColor;
	else if (color == 'blue') document.all[layer].style.color =  hexColor + hexColor + 'ff';

	if (direction == 'up'){
		colorVal += 5;
		if (colorVal == 255){
			direction = 'down';
			if (color == 'red') color = 'blue';
			else if (color == 'blue') color = 'red'; 
		}
	}

	else if (direction == 'down'){
		colorVal -= 5;
		if (colorVal == 0) {
			direction = 'up';
		}
	}
	setTimeout("colorCycle('cycleLayer')", 0);
}

function hex(number){
	return pad(number.toString(16),2);
}

function pad(number, len){
	// add leading zeroes
	var numLen = number.toString().length;
	var result = '';
	for(n=0; n<len-numLen; n++){
		result = '0' + result;
	}
	return result+number;
}






// --------------------- glowlayers (tagline)

glowText = ' You are visitor number one.';
glowStyle= 'font-family: palatino, georgia; font-size: 18px; font-style: italic; color: gray;';
glowSpeed = 100;
glowCounter=0;
oldGlowCounter = glowText.length-1;

function initGlowLayers(){
	if (ie){
		html = '';
		for (i=0; i<glowText.length; i++){
			html += '<img src="/images/pixel.gif" width=2 height=2><span id="glowLayer' + i + '" style="' + glowStyle +'">' + glowText.charAt(i) + '</span>';
		}
		html += '<span id=trademark>TM</span>';
		document.write(html);
	}
	else if (ns){
		document.write('<span id="glowLayer">' + glowText + '</span><span id=trademark>TM</span>');	
	}
}

function glowLayers(){
	if (ie){
		document.all["glowLayer" + oldGlowCounter].style.color	= 'gray';
		document.all["glowLayer" + glowCounter].style.color	= 'white';
		oldGlowCounter = glowCounter;
		glowCounter = (glowCounter + 1) % glowText.length;
		if (glowCounter == 1) window.setTimeout("glowLayers()", glowSpeed*glowText.length);
		else window.setTimeout("glowLayers()", glowSpeed)
	}
}





// ----------------------- itime


function init_iTime(){
	if (ie){
		itid = window.setTimeout("update_iTime()", 0);
	}
}


function update_iTime(){
	
	now = new Date();
	iTime = (Math.floor(((now.getHours()*3600000)+(now.getMinutes()*60000)+((now.getTimezoneOffset()/60)+1)*3600000)/86400));
	iTime = iTime % 1000;
	iTimeString = pad(iTime.toString(), 3);
	
	document.all["iTimeLayer"].innerText = iTimeString;
		
	itid = window.setTimeout("update_iTime()", 5000);
}

//--------------page specific

function redirect(destination){
	if (document.location.href.indexOf(destination) == -1) document.location = destination;
}
function popInfo(){	
	window.open("/about.html","info","width=400,height=320,top=0,left=0,toolbar=no,location=no,status=no,directories=no,menubar=no,scrollbars=no,resizable=no");
}
function popAnnounce(){	
	window.open("/announce.html","announce","width=400,height=320,top=0,left=0,toolbar=no,location=no,status=no,directories=no,menubar=no,scrollbars=no,resizable=no");
}
function popNews(){	
	window.open("/news.html","info","width=400,height=240,top=0,left=0,toolbar=no,location=no,status=no,directories=no,menubar=no,scrollbars=no,resizable=no");
}
function popPrefs(){	
	window.open("/prefs.html","info","width=400,height=180,top=0,left=0,toolbar=no,location=no,status=no,directories=no,menubar=no,scrollbars=no,resizable=no");
}
function popDutch(){	
	window.open("/dutch.html","info","width=400,height=320,top=0,left=0,toolbar=no,location=no,status=no,directories=no,menubar=no,scrollbars=no,resizable=no");
}
function popITime(){	
	window.open("/old/itime.html","info","width=400,height=320,top=0,left=0,toolbar=no,location=no,status=no,directories=no,menubar=no,scrollbars=no,resizable=no");
}
function selectToday(){
	document.date.selection.options[1].selected = true;
}

function suggest(){
	bookmark = prompt ('Please enter the URL:', '');
	if (bookmark != '' && bookmark != null){
		document.location.href='http://www.monospace.com/pcgi-bin/suggest.cgi?bookmark=' + bookmark;
		alert("Thank you!");
	}
}


function initPage(){
//	selectToday(); 
//	document.google.q.focus();  
//	if (ie) colorCycle('cycleLayer');
//	init_iTime();
}

// ---------------------holiday lights
/*
lightsOn = new Array(); lightsOff = new Array();
lightsOn[0] = new Image(); lightsOn[0].src = "/images/lights/red_on.gif";
lightsOff[0] = new Image(); lightsOff[0].src = "/images/lights/red_off.gif";
lightsOn[1] = new Image(); lightsOn[1].src = "/images/lights/green_on.gif";
lightsOff[1] = new Image(); lightsOff[1].src = "/images/lights/green_off.gif";
lightsOn[2] = new Image(); lightsOn[2].src = "/images/lights/blue_on.gif";
lightsOff[2] = new Image(); lightsOff[2].src = "/images/lights/blue_off.gif";
lightsOn[3] = new Image(); lightsOn[3].src = "/images/lights/yellow_on.gif";
lightsOff[3] = new Image(); lightsOff[3].src = "/images/lights/yellow_off.gif";

numberOfLights = 20;
allLights = new Array(numberOfLights);
function initLights(){
	document.write(table(1, numberOfLights));
	blinkLights();
}

var lightCounter = 0;
function table(rows, cols){
	html = "<table width=100%>";
	for (var i=1; i<=rows; i++){
		html += "<tr>";
		for (var j=1; j<=cols; j++){
			var color = rnd(4);
			allLights[lightCounter] = color;
			html += "<td>";
			html += "<img name=\"light" + lightCounter + "\" src=\"" + lightsOff[color].src + "\" width=24 height=24>"; 				
			html += "</td>";
			lightCounter++;
		}
		html += "</tr>";
	}
	html += "</table>";
	return html;
}

function blinkLights(){
	for (var thisLight=0; thisLight<numberOfLights; thisLight++){
		thisLightColor = allLights[thisLight];
		if (coinFlip()) document.images["light" + thisLight].src = lightsOn[thisLightColor].src;
		else document.images["light" + thisLight].src = lightsOff[thisLightColor].src;
	}
	window.setTimeout("blinkLights()", 100);
}
*/
function coinFlip(){
	return (Math.random() < Math.random())? true: false;
}

function rnd(number){
	return Math.floor(Math.random() * number);
}
