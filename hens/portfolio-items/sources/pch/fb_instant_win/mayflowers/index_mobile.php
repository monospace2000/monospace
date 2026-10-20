<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>PCH.com</title>

<meta name="viewport" content="width=480" />
<meta name="apple-mobile-web-app-capable" content="yes" />
	
<script type="text/javascript" src="https://spectrum.pch.com/serverdate.aspx"></script>
<script>
	// date math to load the correct game for the current month:
	var dev = document.location.href.indexOf(".dev");
	var now = new Date(SERVER_DATE);
	var months = ["january", "february", "march", "april", "may", "june", "july", "august", "september", "october", "november", "december"];
	var thisMonth = months[now.getMonth()];
	var thisYear = now.getFullYear();
	var gameURL = "https://fbapp.pch.com/app_iw_game_" + thisMonth + thisYear + "/index_mobile.php"
	if (dev==-1 && document.location.href != gameURL) document.location.href = gameURL;
</script>

<script src="/libraries/scripts/phaser.min.js"></script>
<script src="/libraries/scripts/phaserDebugArcadePhysics.js"></script>
<script src="/libraries/scripts/jquery-1.6.4.min.js"></script>
<script src="/libraries/scripts/jquery.cookies.2.2.0.min.js"></script>

<style>
body{
	margin: 0;
	font-family: sans-serif;
}
canvas {   display : block;   margin : auto;}
#header{
	width:480px;
	height:auto;
	margin: 0 auto;	
	background-color: #000;
}
#rules{
	color: #fff;
	font-weight:bold;
	font-size:13px;	
	text-align:right;
	padding:3px;
}
#rules a{
	color: #fff;
	text-decoration:none;	
}
#footer{
	position:absolute;
	top: 650px;
	width:100%;
	height:auto;
}
#disclaimer{
	font-size:10px;	
	width:450px;
	height:auto;
	text-align:center;
	margin: 20px auto;
}
</style>
</head>

<body>

<div id="header">
	<div id="rules">
	<a href="javascript:showRules()">Official Rules</a> | <a href="javascript:showFacts()">See Sweepstakes Facts</a>
	</div>
</div>


<div id="footer">
	<div id="disclaimer">
		<p>This promotion is in no way sponsored, endorsed or administered by, or associated with, Facebook. You are providing your information to Publishers Clearing House and not to Facebook. No purchase or payment necessary to enter. Buying won't help you win.</p><p> 
	</div>
</div>

<script>
if ( $.cookies.get("pch_fb_iw")!=null){
	// already played	
	document.location.href = "already_played.php"
}
</script>






<script>
// modify these as needed:
var introText = "Water the flowers and YOU could grow up to $1,000 richer!";
var winText = "You must have a green thumb<username>!\nClick \"Continue\" to see your reward!"; 
var giveawayNumbers = "Gwy. Nos. 10184, 11477, 11478, 11479";
//var deviceID = "8283"; //desktop
var deviceID = "8286"; //mobile
var instantWinURL = "http://www.pch.com/gameplay/mini-game/facebook-monthly-game?utm_source=facebook&utm_medium=pchcom_fb_pch&utm_campaign=41_facebookexclusive&tsrc=pchcom_fb_pch&tsrc2=41_facebookexclusive";


///// DO NOT MODIFY BELOW ////////////////////////////////////////////////////

var mx, my;

window.onload = function() {

var game = new Phaser.Game(480, 640, Phaser.AUTO, '', { preload: preload, create: create, update: update, render: render });

	
var gameActive = false;
var gameOver = false;
var feedback;
var msgTxt;

var u; // horizontal center
var v; // vertical center;

var top = 0;
var left = 0;
var right;
var bottom;

var offset = 80;
	
/////////////////////////  GAME SPECIFIC  //////////////////////////////


var canScale = 0.5; // scale of watering can

var plants = []; // list of plants on stage
var maxPlants = 20; // number of plants on stage
var plantsGrown = 0; // number of fully grown plants
var baseScale = 0.9; // scale factor: percentage of original graphic
var scaleVar = 0.3// scale variation: +/- percentage of scaled graphic 

var maxLayers = 5; // number of soil depth layers
var soilHeight = 160; // height of soil sprite
var layerHeight = soilHeight/maxLayers; // height of soil depth layers
var layers = []; // list of soil depth layers



var growSpeed = 800; // update time in ms
var growSpeedVar = 0.15; // +/- variation on grow speed


var plantsLib = [
	{name: "plant1", weight: 10, scale: 0.7},
	{name: "plant2", weight: 15, scale: 0.8},
	{name: "plant3", weight: 12, scale: 0.9},
	{name: "plant4", weight: 8, scale: 0.8},
	{name: "plant5", weight: 5, scale: 0.7},
	{name: "plant6", weight: 105, scale: 0.7},
	{name: "plant7", weight: 105, scale: 0.7}
];
var numChoices = plantsLib.length;
var sumOfWeight = 0;
for(var i=0; i<numChoices; i++) {
	sumOfWeight += plantsLib[i].weight;
}

function preload () {
	var cb = new Date(); // cache buster


	game.load.image('title', 'images/gametitle_mobile.png?'+cb);
	game.load.image('logo', 'images/logo.png?'+cb);
	game.load.image('logo_white', 'images/logo_white.png?'+cb);
	game.load.image('sash', 'images/sash.png?'+cb);

	game.load.image('pixel', 'images/pixel.gif');
	game.load.image('pixel0', 'images/pixel0.gif');
	game.load.image('crosshairs', 'images/crosshairs.png');
	game.load.image('button_playnow', 'images/button_playnow.png?'+cb);
	game.load.image('button_continue', 'images/button_continue.png?'+cb);
	
		game.load.image('background', 'images/background.jpg?'+cb);

	
	// game specific ////////////////////////////////////////////////////////
	game.load.image('can', 'images/can.png?'+cb);
	game.load.image('water', 'images/pixel_blue.gif?'+cb);
	game.load.image('soil', 'images/soil.png?'+cb);
	game.load.image('brush', 'images/brush2.png?'+cb);
	game.load.image('sky', 'images/sky.png?'+cb);
	game.load.image('fence', 'images/fence.png?'+cb);
	game.load.image('fence2', 'images/fence2.png?'+cb);
	game.load.image('grass', 'images/grass.png?'+cb);
	game.load.image('sun', 'images/sun.png?'+cb);
	
	for (var i=0; i<plantsLib.length; i++){
		var name = plantsLib[i].name;
		game.load.atlas(name, 'images/'+name+'.png?'+cb, 'images/'+name+'.json?'+cb);
	}

	//game.load.script('BlurX', 'https://cdn.rawgit.com/photonstorm/phaser/master/v2/filters/BlurX.js');
    //game.load.script('BlurY', 'https://cdn.rawgit.com/photonstorm/phaser/master/v2/filters/BlurY.js');

	//game.load.audio("birds", "sounds/birds.wav");
}

	

function create () {
	
	
	u = game.world.centerX;
	v = game.world.centerY;
	right = game.world.width;
	bottom = game.world.height;
	
	
	game.physics.startSystem(Phaser.Physics.ARCADE); // define physics to be used
	game.world.setBounds(left-offset, top-offset, right+offset, bottom+offset); // use for checking bounds
	//game.physics.arcade.gravity.y = GRAVITY;
	
	
	game.stage.backgroundColor = '#a7cfee';
	//game.plugins.add(Phaser.Plugin.DebugArcadePhysics);
	
	
		background = game.add.sprite(0,0, 'background');

	
	//setup the blur filter
	//blurX = game.add.filter('BlurX');
	//blurY = game.add.filter('BlurY');
	//blurX.blur = 0.5;
    //blurY.blur = 0.5;
			
	


	
	game.input.onDown.add(mouseDown, this);
	game.input.onUp.add(mouseUp, this);	
	//game.input.onTap.add(onTap, this);

	
	
	// INSTANT WIN DETAILS ////////////////////////////////////////////////////////
	
	logo = game.add.sprite(u, 40, "logo_white");
	logo.anchor.setTo(0.5, 0.5);
	logo.fixedToCamera=true;
	

	title = game.add.sprite(u, 180, "title");
	title.anchor.setTo(0.5, 0.5);
	

	
	sash = game.add.sprite(0, 0, "sash");
	
	intro = game.add.text(u, v+100, introText);
	intro.anchor.set(0.5);
	intro.align = 'center';
	intro.font = 'Arial';
	intro.fontWeight = 'bold';
	//intro.fontStyle = "italic";
	intro.fontSize = 24;
	intro.fill = '#006600';
	intro.wordWrap= true;
	intro.wordWrapWidth =  game.world.width - 100;
	intro.setShadow(2, 2, 'rgba(0,0,0,0.3)', 2);
	
	intro.stroke = '#000000';
	intro.strokeThickness = 4;
	intro.fill = '#ffffff';
	
	
	
	
	startButton = game.add.sprite(u, v+200, 'button_playnow')
	startButton.anchor.setTo(0.5, 0.5);
	startButton.inputEnabled = true;
	startButton.input.useHandCursor = true;
	startButton.events.onInputUp.add(function(){
	
		// tweens to clear the start screen

		var tween_sash = game.add.tween(sash).to({alpha:0}, 500, Phaser.Easing.Linear.None, true);
		var tween_title = game.add.tween(title).to({y:-100}, 500, Phaser.Easing.Elastic.In, true);
		var tween_title2 = game.add.tween(title).to({alpha:0}, 500, Phaser.Easing.Linear.None, true);
		var tween_intro = game.add.tween(intro).to({alpha:0}, 500, Phaser.Easing.Linear.None, true);	
				var tween_background = game.add.tween(background).to({alpha:0}, 500, Phaser.Easing.Linear.None, true);	

		var tween_startbutton = game.add.tween(startButton).to({y:bottom+200}, 500, Phaser.Easing.Elastic.In, true);

		tween_startbutton.onComplete.add(startGame);
		//startGame();
		
	}, this);	
	
	
	gwnos = game.add.text(right-20, bottom-20, giveawayNumbers);
	gwnos.anchor.set(1, 0.5);
	gwnos.font = "Arial";
	gwnos.fontWeight = 'normal';
	gwnos.fontSize = 12;
	gwnos.fill = '#ffffff';
	gwnLayer = game.add.group();
	gwnLayer.add(gwnos);
	gwnLayer.fixedToCamera = true;
	

	

}


function startGame(){	
	
		gameActive = true;

	//game.time.advancedTiming = true;
    //game.time.desiredFps = 10;

	//sky = game.add.sprite(u+12, -100, 'sky');
	//sky.anchor.setTo(0.5, 0);
	
	sun = game.add.sprite(u-200, v-200, 'sun');
	sun.anchor.setTo(0.5);


	brush = game.add.sprite(u, bottom-140, 'brush');
	brush.anchor.setTo(0.5, 1);

	soil = game.add.sprite(u, bottom, 'soil');
	soil.anchor.setTo(0.5, 1);

	
	for (var i=0; i<maxLayers; i++){
		layers[i] = game.add.group();
	}

	for (var i=0; i<maxPlants; i++){


		var layer = i%maxLayers;
		
		var plantChoice = pickPlant(); // weighted choice
		var plantName = plantsLib[plantChoice].name;
		var x = game.rnd.between(left+offset, right-offset);
		var y = bottom-soilHeight + (layer*layerHeight) + game.rnd.between(-layerHeight/2, layerHeight/2);
		plants[i] = game.add.sprite(x, y, plantName);
		plants[i].type = plantChoice;
		plants[i].id = i;
		plants[i].frameCount = game.cache.getFrameCount(plantName)
		plants[i].anchor.setTo(0.5, 1); // set anchor to center,bottom
		plants[i].grown = false;

		//scaling:
		var depthScale = (layer+1)/10;
		var rndScale = baseScale * plantsLib[plantChoice].scale + scaleVar * depthScale;
		plants[i].scale.setTo(rndScale*flip(), rndScale);
		

		//if (layer==maxLayers-1){
		//	console.log("layer: " + layer + ", depth scale: " + depthScale + ", rnd scale: " + rndScale)
		//}
		
		//plants[i].tint = 0x000000-1;
		
		//timer stuff:
		plants[i].timer = game.time.create(false);  //false = don't delete events associated when stopped or paused
		var rndDelay = growSpeed + flip()*Math.random()*(growSpeed*growSpeedVar);
		plants[i].timer.loop(rndDelay, growPlant, this, i);
		plants[i].timer.start();
		plants[i].timer.pause();
		
		//plants[i].updates = 0; // timer updates
		
		layers[layer].add(plants[i]);

	}

/*	
	for (var i=1; i<maxLayers; i++){
		layers[i].visible = false;
	}
*/
	fenceRight = game.add.sprite(right-120, bottom-300, 'fence');
	fenceLeft = game.add.sprite(left-80, bottom-300, 'fence2');
	fenceRight.x = right-200;
	fenceLeft.x = left+10
	layers[0].add(fenceRight)
	layers[0].add(fenceLeft)

	logoLayer = game.add.group();
	logoLayer.add(logo);
	
	wateringCan = game.add.sprite(u, v, 'can');
	wateringCan.anchor.setTo(0.5);
	wateringCan.scale.setTo(0.5);
	wateringCan.alpha = 0;

	tween_can = game.add.tween(wateringCan).to({alpha:1}, 500, Phaser.Easing.Linear.None, true);
	tween_can.onComplete.add(function(){
	});	
	
//	setTimeout(function(){
	
	//////////////////  particle emitter //////////////////////////////
		
		waterEmitter = game.add.emitter(left, top, 2000);

	//makeParticles(keys [, frames] [, quantity] [, collide] [, collideWorldBounds] [, particleArguments])
	
		waterEmitter.makeParticles('water', 0, 3000, false, false);
		waterEmitter.width = 30;
		waterEmitter.height = 30;
		waterEmitter.bounce.setTo(0.5, 0.5);
		//waterEmitter.minParticleSpeed.setTo(-100, 30);
		//waterEmitter.maxParticleSpeed.setTo(100, 100);
		waterEmitter.minParticleScale = 2.0;
		waterEmitter.maxParticleScale = 5.0;
		//waterEmitter.minRotation = 0;
		//waterEmitter.maxRotation = 0;
		waterEmitter.gravity = 750;
		waterEmitter.setXSpeed(-100, -200);
		waterEmitter.setYSpeed(-50, 50);


		//flow(lifespan, frequency, quantity, total, immediate) 
		waterEmitter.flow(700, 10, 10, -1);
		waterEmitter.on = false;

		// explode, lifespan, frequency, quantity
	   // waterEmitter.start(false, 40000, 100);

//	}, 500);

	grass = game.add.sprite(u, bottom+10, 'grass');
	grass.anchor.setTo(0.5, 1);
	


	//gameActive = true;
}

function growPlant(i) {

	var thisPlant = plants[i];
	if (thisPlant.grown) return;
	
	//thisPlant.updates++;
	if (thisPlant.frame<thisPlant.frameCount-1) {
		thisPlant.frame++;
	}else{
		thisPlant.grown = true;
		plantsGrown++;
		if (plantsGrown>=maxPlants) {
			wateringCan.angle = 0;
			waterEmitter.on = false;
			endGame();
		}
	}
}


function pickPlant(){
	//weighted random choice

	var rnd = game.rnd.between(0, sumOfWeight-1);
	//console.log(rnd);
	for(var i=0; i<numChoices; i++) {
  		if(rnd < plantsLib[i].weight){
			return i;
		}
  		rnd -= plantsLib[i].weight;
	}
	return i;
}
	
function mouseOver(sprite){
	if (!gameActive) return;
	//showTooltip(sprite.tipText);
}
function mouseOut(sprite){
	if (!gameActive) return;
	//hideTooltip();
}
	
function mouseDown(){
	if (!gameActive) return;
	
	wateringCan.angle = -30;
	waterEmitter.on = true;
	
	//resume all plant timers
	for (var i=0; i<maxPlants; i++){
		plants[i].timer.resume();
	}
}
function mouseUp(){
	if (!gameActive) return;
	
	wateringCan.angle = 0;
	waterEmitter.on = false;
	
	//pause all plant timers
	for (var i=0; i<maxPlants; i++){
		plants[i].timer.pause();
	}
}

function onTap(pointer, doubleTap) {
    if (doubleTap){
		// double tap/click
    }else{
		//nothing
    }
}

function checkOverlap(spriteA, spriteB) {
    var boundsA = spriteA.getBounds();
    var boundsB = spriteB.getBounds();
    return Phaser.Rectangle.intersects(boundsA, boundsB);
}


	

function textPrompt(x, y, msg){
	if (msgTxt) msgTxt.destroy();
	msgTxt = game.add.text(x, y, msg);
	msgTxt.anchor.set(0.5);
	msgTxt.align = 'center';
	msgTxt.font = 'Arial';
	msgTxt.fontWeight = 'bold';
	msgTxt.fontStyle = "italic";
	msgTxt.fontSize = 20;
	msgTxt.fill = '#006600';
	msgTxt.wordWrap= true;
	msgTxt.wordWrapWidth =  500;
	msgTxt.setShadow(2, 2, 'rgba(0,0,0,0.3)', 2);
	msgTxt.stroke = '#000000';
	msgTxt.strokeThickness = 2;
	msgTxt.fill = '#fff200';
	msgTxt.alpha = 1;
	//var msgTween = game.add.tween(msgTxt).to({alpha:0.5}, 500, Phaser.Easing.Linear.None, true, 0, -1);
	var msgTween = game.add.tween(msgTxt.scale).to({x:1.1, y:1.1}, 500, Phaser.Easing.Circular.None, true, 0, -1);
	msgTween.yoyo(true, 0);
}

var tooltipActive = false;
function showTooltip(txt){
	tooltipActive = true;
	tooltip = game.add.text(mx, my-10, txt)
	tooltip.anchor.set(0.5);
	tooltip.align = 'center';
	tooltip.font = 'Arial';
	//tooltip.fontWeight = 'bold';
	//tooltip.fontStyle = "italic";
	tooltip.stroke = '#000000';
	tooltip.strokeThickness = 1;
	tooltip.fontSize = 16;
	tooltip.fill = '#ffffff';
	tooltip.wordWrap= true;
	tooltip.wordWrapWidth =  400;
	tooltip.setShadow(2, 2, 'rgba(0,0,0,0.3)', 2);
}
function hideTooltip(){
	tooltipActive = false;
	tooltip.x = -right;
	//tooltip.destroy();
}

function collisionHandler(hitbox){
	console.log(hitbox);
}



function update() {
	
	
	
	if (gameActive){
		// store mouse position
		mx = game.input.x;
		my = game.input.y;		
		// tooltip
		if (tooltipActive){
			tooltip.x = mx;
			tooltip.y = my-10;
		}
	
		wateringCan.x = mx;
		wateringCan.y = my;

		// bunch of magic numbers to keep emitter in place:
		waterEmitter.x = wateringCan.x - Math.sin(game.math.degToRad(wateringCan.angle+70)) * 77;
		waterEmitter.y = wateringCan.y + Math.cos(game.math.degToRad(wateringCan.angle+70)) * 77;

		//game.physics.arcade.collide(waterEmitter, floor);
		
		//crosshairs.x = waterEmitter.x;
		//crosshairs.y = waterEmitter.y;
		
		omx = mx;
		omy = my;
	
	
		
	}
}

function render() {
	//	game.debug.text(debugMessage, 32, 32);
	if (gameActive){
	//	game.debug.text(walk.frame, 32, 32);
		//game.debug.text(toys[0].y, 32, 32);
		//game.debug.text(clouds.tilePosition.y, 32, 64);
		 //game.debug.text('Time until event: ' + timer.duration.toFixed(0), 32, 32);
		//game.debug.text('Loop Count: ' + total, 32, 64);
	}
	//game.debug.arcade;
}



function removeSprites(sprite){
	//overlay.destroy();	
	startButton.destroy();	
	intro.destroy();
	title.destroy();
}


function endGame(){
	
	gameActive = false;
  //  game.time.desiredFps = 60;

	var tween_can = game.add.tween(wateringCan).to({alpha:0}, 500, Phaser.Easing.Linear.None, true);

	
	var button = game.add.sprite(u, v+200, 'button_continue');
	button.anchor.setTo(0.5, 0.5);
	button.alpha = 0;
	var tween_button_alpha = game.add.tween(button).to({alpha:1}, 500, Phaser.Easing.Linear.None, true);
	button.inputEnabled = true;
	button.input.useHandCursor = true;
	button.events.onInputUp.add(gotoIW, this);
	
	if (userName==""){
			winText = winText.replace("<username>", "");
		}else{
			winText = winText.replace("<username>", ", "+userName.split(" ")[0]);
		}
	var text = game.add.text(u, v-200, winText);
	text.anchor.set(0.5);
	text.align = 'center';
	text.font = 'Arial';
	text.fontWeight = 'bold';
	text.fontSize = 24;
	text.fill = '#ffffff';
	text.wordWrap= true;
	text.wordWrapWidth =  game.world.width-100;
	text.setShadow(1, 1, 'rgba(0,0,0,0.8)', 3);
	
	text.stroke = '#000000';
    text.strokeThickness = 2;
    text.fill = '#ffffff';	
	text.alpha = 0;
	var text_button_alpha = game.add.tween(text).to({alpha:1}, 500, Phaser.Easing.Linear.None, true);

}

function gotoIW(){
	//document.location.reload();
	//return;
	
	setCookie("alreadyplayed=true");
	window.open(instantWinURL);
	top.location.href = "https://www.facebook.com/pch";	
	//fbshare();	
}
function flip(){
	return Math.round(Math.random()) * 2 - 1;
}
function approach(source, target){
	if (Math.abs(source-target)>1) {
		return (source / 2) + (target / 2);
	} else {
		return target;
	}
}
function shuffle(array) {
	var tmp, current, top = array.length;	
	if(top) while(--top) {
		current = Math.floor(Math.random() * (top + 1));
		tmp = array[current];
		array[current] = array[top];
		array[top] = tmp;
	}
	return array;
};
function randomList(n){
	var tmp = [];
	for (var i=0; i<n; i++){
		tmp[i] = i;	
	}
	return shuffle(tmp);
}


};
</script>


<script>
function setCookie(value){
	// make a call to PHP in order to bypass Safari's third-party cookie policy
    $.ajax({url: 'setcookie.php?value='+value, 
        async: false, dataType: 'text', 
        success: function(text) { 
            result  = text; 
        }, error: function(http, message, exc) { 
            result = message; 
    }}); 
    return result; 
}

function serverTime() { 
    var time = null; 
    $.ajax({url: '../serverTime.php', 
        async: false, dataType: 'text', 
        success: function(text) { 
            time = new Date(text); 
        }, error: function(http, message, exc) { 
            time = new Date(); 
    }}); 
    return time; 
}


// MISC UTILITIES
function getURLValue(variable){
	queryString = document.location.search.substring(1);
	if (queryString == "") return "";
	pairs = queryString.split("&");
	for (i=0; i<pairs.length; i++){
		if (pairs[i].split("=")[0].toLowerCase() == variable.toLowerCase()) return unescape(pairs[i].split("=")[1]);
	}
	return "";
}
function popScroll(url,w,h){
	popwin=window.open(url,"win","scrollbars=yes,toolbar=no,directories=no,menubar=no,resizable=yes,status=no,width="+w+",height="+h+"");
	popwin.window.focus();
}

function showRules(){
	if (dev) rulesURL = "http://rules.pch.com/viewrulesfacts?mailid=IWE-" + deviceID + "&preview=1";
	else rulesURL = "http://rules.pch.com/viewrulesfacts?mailid=IWE-" + deviceID;	
	popScroll(rulesURL, 550, 540);
}
function showFacts(){
	if (dev) factsURL = "http://rules.pch.com/viewrulesfacts?preview=1&mailid=IWE-" + deviceID + "#facts"
	else factsURL = "http://rules.pch.com/viewrulesfacts?mailid=IWE-" + deviceID + "#facts"
	popScroll(factsURL, 550, 540);
}
</script>



<script>
// FACEBOOK STUFF
  	var userName = "";

  window.fbAsyncInit = function() {
	FB.init({
	  appId      : '429350763804931',
	  xfbml      : true,
	  version    : 'v2.10'
	});
	FB.Canvas.setAutoGrow();


	FB.getLoginStatus(function(response) {
		console.log(response);
	  if (response.status === 'connected') {
		// the user is logged in and has authenticated your
		// app, and response.authResponse supplies
		// the user's ID, a valid access token, a signed
		// request, and the time the access token 
		// and signed request each expire
		var uid = response.authResponse.userID;
		var accessToken = response.authResponse.accessToken;
		
		FB.api( '/me', 'get', { access_token : accessToken }, function(response){
			userName = response.name;
			console.log(response.name)
			}
		);
		  
	  } else if (response.status === 'not_authorized') {
		// the user is logged in to Facebook, 
		// but has not authenticated your app
		  FB.login(function(response){
			//console.log(response)
			}); 
	  } else {
		// the user isn't logged in to Facebook.
		  	FB.login(function(response){
			//console.log(response)
			});  
	  }
	 });
	  

	//FB.api('/me', function(response) {
   // 	console.log(response);
	//});
	  
	  
  };

  (function(d, s, id){
	 var js, fjs = d.getElementsByTagName(s)[0];
	 if (d.getElementById(id)) {return;}
	 js = d.createElement(s); js.id = id;
	 js.src = "//connect.facebook.net/en_US/sdk.js";
	 fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));



	

function fbshare(){
		var share = {
		method: 'feed',
		  link: 'https://www.facebook.com/pch/app_676918965671465?app_data=utm_source%3Dsocial_fb_pch%26utm_medium%3DMonthly_Game_Share%26utm_campaign%3DFB_InstantWin',
       	  picture: "https://fbapp.pch.com/app_iw_game_june2017/images/shareImage.jpg",
			name: 'PCH Presents Butterfly Bucks.',
			caption: "Catch a beautiful butterfly to reveal a great reward, up to $1,000!",
			description: 'Play exclusive PCH Instant Win Games right here on Facebook!'

	};

	FB.ui(share, function(response) { 
		//showLoader(false);
		//console.log(response); 
	});
}

</script>


<script type="text/javascript">  
// GOOGLE ANALYTICS
(function() {
	var ga = document.createElement('script');
	ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:'   == document.location.protocol ? 'https://ssl'   : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0];
	s.parentNode.insertBefore(ga, s);
    })
();
</script>
<script type="text/javascript">
// GOOGLE ANALYTICS 
var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-4102833-1']); //Must be placed prior to page view tracker
_gaq.push(['_setDomainName', '.pch.com']); //Must be placed prior to page view tracker
_gaq.push(['_setAllowLinker', true]); //Must be placed prior to page view tracker
_gaq.push(['_trackPageview', '/social_FB_InstantWin/desktop']);
_gaq.push(['_set', 'campaignParams', 'utm_source='+ getURLValue('utm_source') +'&utm_medium=' + getURLValue('utm_medium') + '&utm_campaign=' + getURLValue('utm_campaign') ]);

function gaEvent(eventCategory, eventAction, eventLabel){
	_gaq.push(['_trackEvent', eventCategory, eventAction, eventLabel]);	
}
function gaPage(pageName){
	// virtual page view
	_gaq.push(
		['_trackPageview', pageName]
	);	
}
</script>
	
	
</body>
</html>