

// ----------------------------------------------------
// Copyright (c)1998-2002 monospace. All Rights Reserved.
// You may freely re-use and modify these scripts, 
// but only with this message intact.
// ----------------------------------------------------

if (navigator.appName.indexOf("Netscape") != -1 && navigator.appVersion.indexOf("4.") != -1){
	//top.document.location.href = "sorry.html";
}

if (navigator.appVersion.indexOf("MSIE") != -1) {
	ns = false;
	ie = true;
}else{
	ns = true;
	ie = false;
}
mac = (navigator.platform.indexOf('Mac') != -1);

moz = false;
if (navigator.appName.indexOf("Netscape") != -1){
	v = parseInt(navigator.appVersion);
	moz = (v>=5)? true: false;
}

savedPrefs = unescape(document.cookie);
if (savedPrefs.indexOf("prefs") != -1){
	userPrefs = true;
	tmpArray = savedPrefs.substring(6, savedPrefs.length).split("+");
	prefs = new Array();
	for (var i=0; i<tmpArray.length; i++){
		nameValuePair = tmpArray[i].split("=");
		prefs[nameValuePair[0]] = nameValuePair[1];	
	}
} else {
	userPrefs = false;
}

/*
if (userPrefs){
	// set a "last visited cookie" only if user has prefs
	lastVisited = new Date();
//	lastVisited = lastVisited.toUTCString();
	expDate = new Date();
	expDate = new Date(expDate.setFullYear(expDate.getFullYear()-1));
	document.cookie = "lastVisited=" + escape(lastVisited) + "; expires=" + expDate.toUTCString();
}
*/

function saveName(name){
	expDate = new Date();
	expDate = new Date(expDate.setFullYear(expDate.getFullYear()+1));

	if (! userPrefs) prefs = new Array();
	prefs.name = name;

	var prefsString = "";
	for (var i in prefs){
		prefsString += i + "=" + prefs[i] + "+";
	}
	document.cookie = "prefs=" + escape(prefsString) + "; expires=" + expDate.toUTCString();
	//top.document.location = "http://www.monospace.com/";
	//top.main.document.all.greeting.innerHTML = '<a class=bright target="_self" href="javascript:getNameAgain()" title="Click here if you\'re not ' + prefs.name + '">Hello ' + prefs.name + '!</a><br>';
	document.getElementById("greeting").innerHTML = '<a class="bright" target="_self" href="javascript:getNameAgain()" title="Click here if you\'re not ' + prefs.name + '">Hello ' + prefs.name + '!</a><br>';
}


function redirect(destination){
	var domain ="http://www.monospace.com"; // needed by Netscape
	var fwdPage = domain + "/forward.html";
	
	// external page
	if (destination.indexOf('monospace') == -1 && destination.indexOf('http://') != -1){
		
		// no prefs set: use same window with a delay
		if (!userPrefs){
			document.location = fwdPage + "?" + destination;
		
		// user has prefs
		} else{

			// same window with delay
			if (prefs.linkOptions == "same"){
				document.location = fwdPage + "?" + destination;		
	
			// open new window
			} else{
				window.open(destination, "", "favorites=yes,toolbar=yes,location=yes,status=yes,directories=yes,menubar=yes,scrollbars=yes,resizable=yes");
			}
		}
	
	// internal page
	}else{
		document.location = destination;
	}
}

months = new Array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
days = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
today = new Date();
thisDay = days[today.getDay()];
thisDate = today.getDate().toString();
//alert(thisDate)
/*
lastNumber = thisDate.substring(thisDate.length-1, thisDate.length);
if (thisDate == 11) {
	thisDate = "11th";
}else if (thisDate == 12) {
	thisDate = "12th";
}else if (thisDate == 13) {
	thisDate = "13th";
}else{
	if (lastNumber == 1){
		thisDate += "st";
	}else if (lastNumber == 2){
		thisDate += "nd";
	}else if (lastNumber == 3){
		thisDate += "rd";
	}else{
		thisDate += "th";
	}
}
*/
thisMonth = months[today.getMonth()];
thisYear = today.getFullYear();

function happyBirthday(){
	if (userPrefs){
		if (prefs.birthMonth == thisMonth && prefs.birthDay == thisDate) {
			return true;
		}else return false; 
	}else return false;
}

function showDate(){
	dateString = thisDay + ", " + thisMonth + " " + thisDate + ", " + thisYear;
	document.open();
	document.write(dateString);
	document.close();
}

function welcome(){
	if (userPrefs){
		if (prefs.name != "") {
			greetingtxt = (happyBirthday())? "Happy Birthday ": "Hello ";
			markup = '<a class="bright" target="_self" href="javascript:getNameAgain()" title="Click here if you\'re not ' + prefs.name + '">'+ greetingtxt + prefs.name + '!</a><br>';
		}else{
			markup = '<a class="bright" target="_self" href="javascript:getName()" title="Click to enter your name">Hello! What is your name?</a><br>';
		}
		document.write(markup);
	} else{
		markup = '<a class="bright" target="_self" href="javascript:getName()" title="Click to enter your name">Hello! What is your name?</a><br>';
		document.write(markup);
	}
}

function getName(){
	if (!userPrefs || prefs.name == ""){
		visitorName = prompt("Hello! What is your name?", "");
		if (visitorName != "" && visitorName != null){
			saveName(visitorName);
		}
	}
}

function getNameAgain(){
	visitorName = prompt("Hello! What is your name?", "");
	if (visitorName != "" && visitorName != null){
		saveName(visitorName);
	}
}

function suggest(){
	bookmark = prompt ('Please enter the URL:', '');
	if (bookmark != '' && bookmark != null){
		document.location.href='http://www.monospace.com/pcgi-bin/suggest.cgi?bookmark=' + bookmark;
		alert("Thank you!");
	}
}

function pop(url, name, w, h){	
	var theURL = url;
	var winName = name;
	var width = w;
	var height = h;
	var winl = (screen.width - width) / 2;
	var wint = (screen.height - height) / 2;
	features = 'width='+width+',height='+height+',top='+wint+',left='+winl+',toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no';
	window.open(theURL,winName,features);
}

function pop_scroll(url, name, w, h){	
	var theURL = url;
	var winName = name;
	var width = w;
	var height = h;
	var winl = (screen.width - width) / 2;
	var wint = (screen.height - height) / 2;
	features = 'width='+width+',height='+height+',top='+wint+',left='+winl+',toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes';
	window.open(theURL,winName,features);
}


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

//

function clock(){
	localDate = new Date();
	//
	year = localDate.getFullYear();
	month = months[localDate.getMonth()];
	day = days[localDate.getDay()];
	dat = localDate.getDate();
	dateString = day + ", " + month + " " + dat + ", " + year;
	//
	
	UTCoffset = localDate.getTimezoneOffset()/60; 
	gmt = Date.parse( localDate.toUTCString() ) + UTCoffset*60*60*1000;
	nycDate = new Date(gmt);
	nycDate.setTime(gmt - (4*60*60*1000))
	//
	nych = nycDate.getHours();
	nycm = nycDate.getMinutes();
	nycs = nycDate.getSeconds();
	if (nycs<10) nycs = "0"+nycs;
	if (nycm<10) nycm = "0"+nycm;
	//noon = nych<12? "AM": "PM";
	//nych %=12;
	//if (nych==0) nych = 12;
	nyc_Time = nych + ":" + nycm + ":" + nycs;
	//
	h = localDate.getHours();
	m = localDate.getMinutes();
	s = localDate.getSeconds();
	if (s<10) s = "0"+s;
	if (m<10) m = "0"+m;
	//
	local_Time = h + ":" + m + ":" + s;
	//
	//document.getElementById("dateField").innerHTML = dateString;
	document.getElementById("nycTime").innerHTML = nyc_Time;
	document.getElementById("localTime").innerHTML = local_Time;
	//
	setTimeout("clock()", 999);
}


var calDays = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
var calMonths = [["January", 31], ["February", 28], ["March", 31], ["April", 30], ["May", 31], ["June", 30], ["July", 31], ["August", 31], ["September", 30], ["October", 31], ["November", 30], ["December", 31]]; 
function calendar(month, day, year){
	var now = new Date();
	var then = new Date();
	then.setDate(1);
	var startDay = then.getDay()-1;
	var month = now.getMonth()+1;
	var year = now.getFullYear();
	var numRows = startDay+1 + calMonths[month-1][1] > (7*5)?6:5;
	if (isLeapYear(year)) calMonths[1][1] = 29;
	var isToday = false;
	var counter=0;

	var html = '<div class="link"><div class="category">' + calMonths[month-1][0] + ' ' + year + '</div><table border="0" cellpadding="0" cellspacing="0" class="calendarTable"><tr>';
	for (i=0; i<calDays.length; i++) html += '<td class="daysLabel" width="20">' + calDays[i].substring(0,3) + '</td>';
	for (row=0; row<numRows; row++){
		html += '</tr><tr>';
		for (col=0; col<7; col++){
			if (counter - now.getDate() == startDay) addClass = " today";
			else addClass = "";
			if (counter-startDay > calMonths[month-1][1]) html += '<td class="daysInactive">&nbsp;</td>';
			else if (counter>startDay) html += '<td class="days' + addClass + '">' + (counter-startDay) + '</td>';
			else html += '<td class="daysInactive">&nbsp;</td>';
			counter++;
		}
	}
	html += '</tr></table>';
	html += '<div align="right">';
	//<div id="dateField"></div>
	html += '<div><span class="gray">Time in New York: </span><span id="nycTime"></span></div>';
	html += '<div><span class="gray">Your Local Time: </span><span id="localTime"></span></div>';
	html += '</div></div>';


	document.getElementById("displayCalendar").innerHTML = html;
}
function isLeapYear(year){
	return ( year % 4 == 0 && (year % 100 != 0 || year % 400 == 0) )
}
function doWord(word){
	window.word.document.location = "/dutchwords/" + word + ".mp3"
}