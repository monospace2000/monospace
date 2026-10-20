<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<title>PCH.com</title>

	<script type="text/javascript" src="https://spectrum.pch.com/serverdate.aspx"></script>
	<script>
		// date math to load the correct game for the current month:
		var dev = document.location.href.indexOf( ".dev" );
		var now = new Date( SERVER_DATE );
		var months = [ "january", "february", "march", "april", "may", "june", "july", "august", "september", "october", "november", "december" ];
		var thisDate = now.getDate();
		var thisMonth = months[ now.getMonth() ];
		var thisYear = now.getFullYear();
		var gameURL = "https://fbapp.pch.com/app_iw_game_" + thisMonth + thisYear + "/index.php";
		if ( dev == -1 && document.location.href != gameURL ) document.location.href = gameURL;
	</script>



	<script src="/libraries/scripts/phaser.min.js"></script>
	<script src="/libraries/scripts/phaserDebugArcadePhysics.js"></script>
	<script src="/libraries/scripts/jquery-1.6.4.min.js"></script>
	<script src="/libraries/scripts/jquery.cookies.2.2.0.min.js"></script>


	<style>
		body {
			margin: 0;
			font-family: sans-serif;
		}
		
		canvas {
			display: block;
			margin: auto;
		}
		
		#header {
			width: 820px;
			height: auto;
			margin: 0 auto;
			background-color: #000;
		}
		
		#rules {
			color: #fff;
			font-weight: bold;
			font-size: 13px;
			text-align: right;
			padding: 3px;
		}
		
		#rules a {
			color: #fff;
			text-decoration: none;
		}
		
		#footer {
			position: absolute;
			top: 650px;
			width: 100%;
			height: auto;
		}
		
		#disclaimer {
			font-size: 10px;
			width: 700px;
			height: auto;
			text-align: center;
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
			<p>This promotion is in no way sponsored, endorsed or administered by, or associated with, Facebook. You are providing your information to Publishers Clearing House and not to Facebook. No purchase or payment necessary to enter. Buying won't help you win.</p>
			<p>
		</div>
	</div>

	<script>
		if ( $.cookies.get( "pch_fb_iw" ) != null ) {
			// already played	
			//document.location.href = "already_played.php"
		}
	</script>




	<script>
		// modify these as needed:
		var introText = "INTRODUCTION";
		var winText = 'GAME OVER';
		var giveawayNumbers = "Gwy. Nos. 10184, 13450, 13451, 3452, 13453";


		
		var deviceID = "13250"; //desktop
		//var deviceID = "13253"; //mobile
		var instantWinURL = "http://www.pch.com/gameplay/mini-game/facebook-monthly-game?utm_source=facebook&utm_medium=pchcom_fb_pch&utm_campaign=41_facebookexclusive&tsrc=pchcom_fb_pch&tsrc2=41_facebookexclusive";


		///// DO NOT MODIFY BELOW ////////////////////////////////////////////////////



		window.onload = function () {

			var game = new Phaser.Game( 820, 640, Phaser.AUTO, '', {
				preload: preload,
				create: create,
				update: update,
				render: render
			} );

			var RAD = Math.PI / 180;


			var gameActive = false;
			var gameOver = false;
			var feedback;
			var msgTxt;
			var sceneLoaded = false;

			var u; // horizontal center
			var v; // vertical center;
			var mx = 0;
			var my = 0;

			var top = 0;
			var left = 0;
			var right;
			var bottom;

			var offsetLeft = 200;
			var offsetRight = 200;

			/////////////////////////   GAME SPECIFIC  //////////////////////////////

			var ballSpeed = 1000;
			var ballTween = Phaser.Easing.Circular.In;
			var maxBlur = 40;
			var hit = false;


			function preload() {
				var cb = new Date();


				game.load.image( 'title', 'images/gametitle.png?' + cb );
				game.load.image( 'logo', 'images/logo.png?' + cb );
				game.load.image( 'logo_white', 'images/logo_white.png?' + cb );
				game.load.image( 'sash', 'images/sash.png?' + cb );

				game.load.image( 'pixel', 'images/pixel.gif' );
				game.load.image( 'pixel0', 'images/pixel0.gif' );
				game.load.image( 'crosshairs', 'images/crosshairs.png' );
				game.load.image( 'gear', 'images/gear.png' );
				game.load.image( 'button_playnow', 'images/button_playnow.png?' + cb );
				game.load.image( 'button_continue', 'images/button_continue.png?' + cb );

				game.load.image( 'intro', 'images/intro.png?' + cb );
				game.load.image( 'outro', 'images/outro.png?' + cb );

				
				game.load.image( 'background', 'images/background.png?' + cb );
				game.load.image( 'ball', 'images/ball.png?' + cb );
				game.load.image( 'kapow', 'images/kapow.png?' + cb );

				game.load.atlas( 'pitcher', 'images/pitcher.png?' + cb, 'images/pitcher.json?' + cb );
				game.load.atlas( 'batter', 'images/batter.png?' + cb, 'images/batter.json?' + cb );

				game.load.script( 'BlurX', '/libraries/scripts/phaser_filters/BlurX.js?' + cb );
				game.load.script( 'BlurY', '/libraries/scripts/phaser_filters/BlurY.js?' + cb );

			}



			function create() {
				u = game.world.centerX;
				v = game.world.centerY;
				right = game.world.width;
				bottom = game.world.height;

				game.stage.backgroundColor = '#bfcccc'
				game.stage.backgroundColor = '#dfebe9';
				//////////////////////////////////////////////////

//				blurX = game.add.filter( 'BlurX' );
//				blurY = game.add.filter( 'BlurY' );
//				blurX.blur = 0;
//				blurY.blur = 0;


				/////////////////////////////////////////////////
				background = game.add.sprite( u, v, 'background' );
				background.anchor.setTo( 0.5 );
				background.scale.setTo( 0.70 );
				
				tmpBatter = game.add.sprite( 60, v, 'batter' );
				tmpBatter.anchor.setTo( 0.5 );
				tmpBatter.scale.setTo(1.2);	
				
				tmpPitcher = game.add.sprite( right-100, v-60, 'pitcher' );
				tmpPitcher.anchor.setTo( 0.5 );
				tmpPitcher.scale.setTo(0.7);	
				
				
				logo = game.add.sprite( u, 40, "logo_white" );
				logo.anchor.setTo( 0.5, 0.5 );
				logo.fixedToCamera = true;
				logoLayer = game.add.group();
				logoLayer.add( logo );

				title = game.add.sprite( u, v-40, "title" );
				title.anchor.setTo( 0.5);

				sash = game.add.sprite( 0, 0, "sash" );


/*				intro = game.add.text( u, v + 100, introText );
				intro.anchor.set( 0.5 );
				intro.align = 'center';
				intro.font = 'Arial';
				intro.fontWeight = 'bold';
				//intro.fontStyle = "italic";
				intro.fontSize = 24;
				intro.fill = '#006600';
				intro.wordWrap = true;
				intro.wordWrapWidth = 500;
				intro.setShadow( 2, 2, 'rgba(0,0,0,0.3)', 2 );

				intro.stroke = '#000000';
				intro.strokeThickness = 4;
				intro.fill = '#ffffff';
*/
				
				intro = game.add.sprite(u, v+100, 'intro');
				intro.anchor.setTo(0.5);


				startButton = game.add.sprite( u, v + 225, 'button_playnow' )
				startButton.anchor.setTo( 0.5, 0.5 );
				startButton.inputEnabled = true;
				startButton.input.useHandCursor = true;
				startButton.events.onInputUp.add( function () {


					var tween_sash = game.add.tween( sash ).to( {
						alpha: 0
					}, 500, Phaser.Easing.Linear.None, true );

					var tween_title = game.add.tween( title ).to( {
						y: -200
					}, 500, Phaser.Easing.Elastic.In, true );
					var tween_intro = game.add.tween( intro ).to( {
						alpha: 0
					}, 500, Phaser.Easing.Linear.None, true );
					var tween_tmpBatter = game.add.tween( tmpBatter ).to( {
						alpha: 0
					}, 500, Phaser.Easing.Linear.None, true );					
					var tween_tmpPitcher = game.add.tween( tmpPitcher ).to( {
						alpha: 0
					}, 500, Phaser.Easing.Linear.None, true );
					var tween_startbutton = game.add.tween( startButton ).to( {
						y: bottom + 200
					}, 500, Phaser.Easing.Elastic.In, true );


					tween_startbutton.onComplete.add( function () {
						setTimeout( initGame, 500 );
					} );

				}, this );


				gwnos = game.add.text( right - 20, bottom - 20, giveawayNumbers );
				gwnos.anchor.set( 1, 0.5 );
				gwnos.font = "Arial";
				gwnos.fontWeight = 'normal';
				gwnos.fontSize = 12;
				gwnos.fill = '#ffffff';
				gwnLayer = game.add.group();
				gwnLayer.add( gwnos );
				gwnLayer.fixedToCamera = true;



				sceneLoaded = true;




			}



			function makeDraggable( sprite ) {
				sprite.inputEnabled = true;
				sprite.input.enableDrag(); //  enableDrag parameters = (lockCenter, bringToTop, pixelPerfect, alphaThreshold, boundsRect, boundsSprite)
				//sprite.input.setDragLock( true, false )
				sprite.input.useHandCursor = true;
				//sprite.input.enableSnap( 100, v, true, true );
				sprite.events.onDragStart.add( onDragStart, this );
				sprite.events.onDragStop.add( onDragStop, this );
			}

			function makeClickable( sprite ) {
				sprite.inputEnabled = true;
				sprite.input.useHandCursor = true;
				sprite.events.onInputUp.add( function () {

				}, this );

			}

			function onDragStart( sprite ) {
				//console.log(sprite);
			}

			function onDragStop( sprite ) {
				//
			}

			function mouseOver( sprite ) {
				if ( !gameActive ) return;
				//showTooltip(sprite.tipText);
			}

			function mouseOut( sprite ) {
				if ( !gameActive ) return;
				//hideTooltip();
			}

			function mouseDown() {
				if ( !gameActive ) return;

			}

			function mouseUp( editGroup ) {
				if ( !gameActive ) return;


			}

			function onTap( pointer, doubleTap ) {
				if ( doubleTap ) {
					// double tap/click
				} else {
					//nothing
				}
			}

			function checkOverlap( spriteA, spriteB ) {
				var boundsA = spriteA.getBounds();
				var boundsB = spriteB.getBounds();
				return Phaser.Rectangle.intersects( boundsA, boundsB );
			}





			function initGame() {



				pitcher = game.add.sprite( u + 50, v - 50, 'pitcher' );
				pitcher.scale.setTo( 0.5 );
				pitcher.anchor.setTo( 0.5 );
				pitcher.anim = pitcher.animations.add( 'throw', Phaser.Animation.generateFrameNames( 'pitcher', 0, 15, "", 4 ), 15, false );
				pitcher.anim.onComplete.add( function () {
					//pitcher.frame = 0;
				}, this );


				kapow = game.add.sprite( -u, v, 'kapow' );
				kapow.anchor.setTo( 0.5 );

				ball1 = game.add.sprite( pitcher.x, pitcher.y - 50, 'ball' );
				ball1.anchor.setTo( 0.5 );
				//ball.filters = [blurX, blurY]
				ballLayer = game.add.group();
				ballLayer.add( ball1 );
				ballLayer.visible = false;

				ball2 = game.add.sprite( -right, 0, 'ball' );
				ball2.anchor.setTo( 0.5 );
				ball2.scale.setTo(0.2);
				
				
				batter = game.add.sprite( u - 100, v + 50, 'batter' );
				batter.scale.setTo( 0.90 );
				batter.anchor.setTo( 0.5 );
				batter.anim = batter.animations.add( 'hit', Phaser.Animation.generateFrameNames( 'batter', 0, 22, "", 4 ), 15, false );
				batter.anim.onComplete.add( function () {
					batter.frame = 0;
				}, this );
				batterLayer = game.add.group();
				batterLayer.add( batter );

				game.world.bringToTop( logoLayer );
				game.world.bringToTop( gwnLayer );

				startGame();

			}

			function pitch() {
				if ( !gameActive ) return;
				pitcher.animations.play( 'throw' );
				timer.delay = game.rnd.between( 3000, 6000 )

			}

			function startGame() {
				game.tweens.frameBased = true;

				timer = game.time.create( false );
				timer.loop( game.rnd.between( 4000, 6000 ), pitch, this );
				timer.start();

				game.input.onDown.add( function () {
					if ( !gameActive ) return;
					batter.animations.play( 'hit' );
					//game.debug.text( "Pitcher frame: " + pitcher.frame, 32, 32 )
					if (pitcher.frame===5){
						hit = true;
					}
					//pitcher.animations.play( 'throw' );
				}, this );


				gameActive = true;
			}



			function update() {

				if ( sceneLoaded ) {

				}


				if ( gameActive ) {


					mx = game.input.x;
					my = game.input.y;


					dx = u - mx;
					dy = v - my;




					if ( pitcher.anim.isPlaying ) {
						switch ( pitcher.frame ) {
							//case 3:
							//	batter.animations.play( 'hit' );
							//	break;
							case 9 :
								// show ball sprite in pitcher animation frame 7
								ball1.scale.setTo( 0.1 );
								ball1.x = u + 50;
								ball1.y = v - 75;

								// reset blur filter
								//blurX.blur = 0;
								//blurY.blur = 0;

								ballLayer.visible = true;

								// tween the ball position & location
								ball1.twMove = game.add.tween( ball1 ).to( {
									x: u + 100,
									y: v + 500
								}, ballSpeed, Phaser.Easing.Circular.In, true );
								//ball1.twSpin = game.add.tween( ball ).to( {
								//	angle: 270
								//}, ballSpeed, ballTween, true );
								ball1.twScale = game.add.tween( ball1.scale ).to( {
									x: 1,
									y: 1
								}, ballSpeed, Phaser.Easing.Circular.In, true );

								/*
								// tween the blur filter
								blurX.twBlur = game.add.tween(blurX).to({
									blur: maxBlur
								}, ballSpeed, Phaser.Easing.Linear.In, true)
								blurY.twBlur = game.add.tween(blurY).to({
									blur: maxBlur
								}, ballSpeed, Phaser.Easing.Linear.In, true)
						
								ball1.twScale.onUpdateCallback(function(){
									//do something every tween update
								}, this);
								*/

								ball1.twScale.onComplete.add( function () {
									hit = false;
									//ballLayer.visible = false; // hide ball
									pitcher.frame = 0; // reset pitcher animation
									game.world.bringToTop( batterLayer ); // move batter to front
								}, this );

								break;
							case 12:
								game.world.bringToTop( ballLayer );
								break;
							case 14:
								if (hit){
									kapow.x = ball1.x;
									kapow.y = ball1.y;
									ballLayer.visible = false;
									ball2.x = ball1.x;
									ball2.y = ball1.y;
									ball2.scale.setTo(ball1.scale.x);
									ball2.twScale = game.add.tween( ball2.scale ).to( {
										x: 0.01,
										y: 0.01
									}, ballSpeed, Phaser.Easing.Linear.Out, true );
									ball2.twPosition = game.add.tween( ball2 ).to( {
										x: u+160,
										y: top+100
									}, ballSpeed, Phaser.Easing.Linear.Out, true );
									ball2.twPosition.onComplete.add( function () {
										pitcher.frame = 0; // reset pitcher animation
										game.world.bringToTop( batterLayer ); // move batter to front
										ball2.x = -right;
										endGame();
									}, this );
									
								}
								break;
							case 15:
								kapow.x = -right;
								break;
							default:
								//nothing
						}
					}




					if ( game.input.activePointer.isDown ) {}





					omx = mx;
					omy = my;

				}

			}



			function render() {
				//game.debug.inputInfo(32, 32);
				//game.debug.text( "Game Active: " + gameActive, 32, 32 );
				if ( gameActive ) {

					//if ( pitcher.anim.isPlaying ) {
					//	game.debug.text( "Pitcher frame: " + pitcher.frame, 32, 32 )
					//}
					//if ( batter.anim.isPlaying ) {
					//	game.debug.text( "Batter frame: " + batter.frame, 32, 64 )
					//}

					//game.debug.text( "Leaves remaining: " + Math.round(( cutLeaves/maxLeaves )*100) + "%", 32, 32 )

					/*				    game.debug.spriteInfo(spinner, 32, 32);
    game.debug.text('angularVelocity: ' + spinner.body.angularVelocity, 32, 200);
    game.debug.text('angularAcceleration: ' + spinner.body.angularAcceleration, 32, 232);
    game.debug.text('angularDrag: ' + spinner.body.angularDrag, 32, 264);
    game.debug.text('deltaZ: ' + spinner.body.deltaZ(), 32, 296);
	*/

					//game.debug.text( "Speed: " + SPEED, 32, 32 );
					//					game.debug.text( "Delta X: " + ball.deltax, 32, 32 );
					//					game.debug.text( "Delta Y: " + ball.deltay, 32, 48 );
					//game.debug.text( "Velocity: " + ball.body.velocity, 32, 64 );
					//	game.debug.pointer(game.input.activePointer)

				}
				//game.debug.arcade;
			}





			function removeSprites( sprite ) {
				//overlay.destroy();	
				startButton.destroy();
				intro.destroy();
				title.destroy();
			}


			function endGame() {
				gameActive = false;
				sceneLoaded = false;

				setTimeout( function () {

			
					
					outro = game.add.sprite( u, v + 100, 'outro' );
					outro.anchor.setTo( 0.5 );
					
					var button = game.add.sprite( u, v + 225, 'button_continue' );
					button.anchor.setTo( 0.5, 0.5 );
					button.alpha = 0;
					var tween_button_alpha = game.add.tween( button ).to( {
						alpha: 1
					}, 500, Phaser.Easing.Linear.None, true );
					button.inputEnabled = true;
					button.input.useHandCursor = true;
					button.events.onInputUp.add( gotoIW, this );


					/*

					if ( userName == "" ) {
						winText = winText.replace( "<userName>", "" );
					} else {
						winText = winText.replace( "<userName>", ", " + userName.split( " " )[ 0 ] );
					}
					var text = game.add.text( u, v - 50, winText );
					text.anchor.set( 0.5 );
					text.align = 'center';
					text.font = 'Arial';
					text.fontWeight = 'bold';
					text.fontSize = 24;
					text.fill = '#ffffff';
					text.wordWrap = true;
					text.wordWrapWidth = 400;
					text.setShadow( 1, 1, 'rgba(0,0,0,0.8)', 3 );

					text.stroke = '#000000';
					text.strokeThickness = 2;
					text.fill = '#ffffff';
					text.alpha = 0;
					var text_button_alpha = game.add.tween( text ).to( {
						alpha: 1
					}, 500, Phaser.Easing.Linear.None, true );
					*/

				}, 1000 );

			}

			function gotoIW() {
				//document.location.reload();
				//return;

				setCookie( "alreadyplayed=true" );
				window.open( instantWinURL );
				window.top.location.href = "https://www.facebook.com/pch";
				//fbshare();	
			}

			function plusMin() {
				if ( Math.random() > Math.random() ) return 1;
				else return -1;
			}

			function approach( source, target ) {
				if ( Math.abs( source - target ) > 1 ) {
					return ( source / 2 ) + ( target / 2 );
				} else {
					return target;
				}
			}

			function shuffle( array ) {
				var tmp, current, top = array.length;
				if ( top )
					while ( --top ) {
						current = Math.floor( Math.random() * ( top + 1 ) );
						tmp = array[ current ];
						array[ current ] = array[ top ];
						array[ top ] = tmp;
					}
				return array;
			};

			function randomList( n ) {
				var tmp = [];
				for ( var i = 0; i < n; i++ ) {
					tmp[ i ] = i;
				}
				return shuffle( tmp );
			}


		};
	</script>


	<script>
		function setCookie( value ) {
			// make a call to PHP in order to bypass Safari's third-party cookie policy
			$.ajax( {
				url: 'setcookie.php?value=' + value,
				async: false,
				dataType: 'text',
				success: function ( text ) {
					result = text;
				},
				error: function ( http, message, exc ) {
					result = message;
				}
			} );
			return result;
		}

		function serverTime() {
			var time = null;
			$.ajax( {
				url: '../serverTime.php',
				async: false,
				dataType: 'text',
				success: function ( text ) {
					time = new Date( text );
				},
				error: function ( http, message, exc ) {
					time = new Date();
				}
			} );
			return time;
		}


		// MISC UTILITIES
		function getURLValue( variable ) {
			queryString = document.location.search.substring( 1 );
			if ( queryString == "" ) return "";
			pairs = queryString.split( "&" );
			for ( i = 0; i < pairs.length; i++ ) {
				if ( pairs[ i ].split( "=" )[ 0 ].toLowerCase() == variable.toLowerCase() ) return unescape( pairs[ i ].split( "=" )[ 1 ] );
			}
			return "";
		}

		function popScroll( url, w, h ) {
			popwin = window.open( url, "win", "scrollbars=yes,toolbar=no,directories=no,menubar=no,resizable=yes,status=no,width=" + w + ",height=" + h + "" );
			popwin.window.focus();
		}

		function showRules() {
			if ( dev ) rulesURL = "http://rules.pch.com/viewrulesfacts?mailid=IWE-" + deviceID + "&preview=1";
			else rulesURL = "http://rules.pch.com/viewrulesfacts?mailid=IWE-" + deviceID;
			popScroll( rulesURL, 550, 540 );
		}

		function showFacts() {
			if ( dev ) factsURL = "http://rules.pch.com/viewrulesfacts?preview=1&mailid=IWE-" + deviceID + "#facts"
			else factsURL = "http://rules.pch.com/viewrulesfacts?mailid=IWE-" + deviceID + "#facts"
			popScroll( factsURL, 550, 540 );
		}
	</script>


	<script>
		// FACEBOOK STUFF
		var userName = "";

		window.fbAsyncInit = function () {
			FB.init( {
				appId: '429350763804931',
				xfbml: true,
				version: 'v2.10'
			} );
			FB.Canvas.setAutoGrow();


			FB.getLoginStatus( function ( response ) {
				//console.log(response);
				if ( response.status === 'connected' ) {
					// the user is logged in and has authenticated your
					// app, and response.authResponse supplies
					// the user's ID, a valid access token, a signed
					// request, and the time the access token 
					// and signed request each expire
					var uid = response.authResponse.userID;
					var accessToken = response.authResponse.accessToken;

					FB.api( '/me', 'get', {
						access_token: accessToken
					}, function ( response ) {
						userName = response.name;
						//console.log(response)
					} );

				} else if ( response.status === 'not_authorized' ) {
					// the user is logged in to Facebook, 
					// but has not authenticated your app
					FB.login( function ( response ) {
						//console.log(response)
					} );
				} else {
					// the user isn't logged in to Facebook.
					FB.login( function ( response ) {
						//console.log(response)
					} );
				}
			} );


			//FB.api('/me', function(response) {
			// 	console.log(response);
			//});


		};

		( function ( d, s, id ) {
			var js, fjs = d.getElementsByTagName( s )[ 0 ];
			if ( d.getElementById( id ) ) {
				return;
			}
			js = d.createElement( s );
			js.id = id;
			js.src = "//connect.facebook.net/en_US/sdk.js";
			fjs.parentNode.insertBefore( js, fjs );
		}( document, 'script', 'facebook-jssdk' ) );





		function fbshare( pumpkin, mouth, eyes ) {
			imageID = ( pumpkin * 25 + mouth * 5 + eyes + 1 ).toString();
			if ( imageID.length == 1 ) imageID = "000" + imageID;
			else if ( imageID.length == 2 ) imageID = "00" + imageID;
			else if ( imageID.length == 3 ) imageID = "0" + imageID;

			var share = {
				method: 'feed',
				link: 'https://fbapp.pch.com/app_iw_game_october2018/share.php?shareimage=' + imageID,

			};

			FB.ui( share, function ( response ) {
				//showLoader(false);
				//console.log(response); 
				gaEvent( "/social_FB_InstantWin/", "Facebook_Share", "Success" )
			} );
		}
	</script>


	<!-- GA -->
	<!-- 2018.10.11.2 updated to universal markm -->
	<script>
		( function ( i, s, o, g, r, a, m ) {
			i[ 'GoogleAnalyticsObject' ] = r;
			i[ r ] = i[ r ] || function () {
				( i[ r ].q = i[ r ].q || [] ).push( arguments )
			}, i[ r ].l = 1 * new Date();
			a = s.createElement( o ),
				m = s.getElementsByTagName( o )[ 0 ];
			a.async = 1;
			a.src = g;
			m.parentNode.insertBefore( a, m )
		} )( window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga' );

		ga( 'create', 'UA-4102833-1', 'auto' );
		ga( 'send', 'pageview', '/social_FB_InstantWin/desktop' );
	</script>
	<!-- End Google Analytics -->


</body>
</html>