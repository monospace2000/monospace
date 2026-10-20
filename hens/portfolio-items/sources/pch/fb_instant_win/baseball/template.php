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
		var gameURL = "https://fbapp.pch.com/app_iw_game_" + thisMonth + thisYear + "/index.php"
		if ( !dev && document.location.href != gameURL ) document.location.href = gameURL;
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
		var introText = "Intro Text";
		var winText = "Game Over!";
		var giveawayNumbers = "Gwy. Nos. 10184, 12247, 12248, 12249, 12250";
		var deviceID = "8319"; //desktop
		//var deviceID = "8322"; //mobile
		var instantWinURL = "http://www.pch.com/gameplay/mini-game/facebook-monthly-game?utm_source=facebook&utm_medium=pchcom_fb_pch&utm_campaign=41_facebookexclusive&tsrc=pchcom_fb_pch&tsrc2=41_facebookexclusive";


		///// DO NOT MODIFY BELOW ////////////////////////////////////////////////////



		window.onload = function () {

			var game = new Phaser.Game( 820, 640, Phaser.AUTO, '', {
				preload: preload,
				create: create,
				update: update,
				render: render
			} );


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

			
			
			function preload() {
				var cb = new Date();


				game.load.image( 'title', 'images/gametitle_tmp.png?' + cb );
				game.load.image( 'logo', 'images/logo.png?' + cb );
				game.load.image( 'logo_white', 'images/logo_white.png?' + cb );
				game.load.image( 'sash', 'images/sash.png?' + cb );

				game.load.image( 'pixel', 'images/pixel.gif' );
				game.load.image( 'pixel0', 'images/pixel0.gif' );
				game.load.image( 'crosshairs', 'images/crosshairs.png' );
				game.load.image( 'button_playnow', 'images/button_playnow.png?' + cb );
				game.load.image( 'button_continue', 'images/button_continue.png?' + cb );

			
				//game.load.atlas( 'turkey_hit', 'images/turkey_hit.png?' + cb, 'images/turkey_hit.json?' + cb );
		



			}



			function create() {
				u = game.world.centerX;
				v = game.world.centerY;
				right = game.world.width;
				bottom = game.world.height;

				game.stage.backgroundColor = '#f2fcff'
				game.stage.backgroundColor = '#dfebe9';


				//game.input.onDown.add(mouseDown, this);
				//game.input.onUp.add(mouseUp, this);	
				//game.input.onTap.add(onTap, this);



				logo = game.add.sprite( u, 40, "logo" );
				logo.anchor.setTo( 0.5, 0.5 );
				logo.fixedToCamera = true;
				logoLayer = game.add.group();
				logoLayer.add( logo );

				title = game.add.sprite( u, v, "title" );
				title.anchor.setTo( 0.5, 0.5 );

				sash = game.add.sprite( 0, 0, "sash" );

				intro = game.add.text( u, v + 100, introText );
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




				startButton = game.add.sprite( u, v + 220, 'button_playnow' )
				startButton.anchor.setTo( 0.5, 0.5 );
				startButton.inputEnabled = true;
				startButton.input.useHandCursor = true;
				startButton.events.onInputUp.add( function () {

					var tween_sash = game.add.tween( sash ).to( {
						alpha: 0
					}, 500, Phaser.Easing.Linear.None, true );
					var tween_splash = game.add.tween( splash ).to( {
						alpha: 0
					}, 500, Phaser.Easing.Linear.None, true );
					var tween_title = game.add.tween( title ).to( {
						y: -200
					}, 500, Phaser.Easing.Elastic.In, true );
					var tween_intro = game.add.tween( intro ).to( {
						alpha: 0
					}, 500, Phaser.Easing.Linear.None, true );
					var tween_startbutton = game.add.tween( startButton ).to( {
						y: bottom + 200
					}, 500, Phaser.Easing.Elastic.In, true );


					tween_startbutton.onComplete.add( function () {
						setTimeout( function () {
							startGame();
						}, 100 );
					} );

				}, this );


				gwnos = game.add.text( right - 20, bottom - 20, giveawayNumbers );
				gwnos.anchor.set( 1, 0.5 );
				gwnos.font = "Arial";
				gwnos.fontWeight = 'normal';
				gwnos.fontSize = 12;
				gwnos.fill = '#000000';
				gwnLayer = game.add.group();
				gwnLayer.add( gwnos );
				gwnLayer.fixedToCamera = true;



			}

			function makeDraggable( sprite ) {
				sprite.inputEnabled = true;
				sprite.input.enableDrag(); //  enableDrag parameters = (lockCenter, bringToTop, pixelPerfect, alphaThreshold, boundsRect, boundsSprite)
				sprite.input.setDragLock( true, false )
				sprite.input.useHandCursor = true;
				sprite.input.enableSnap( 100, v, true, true );
				sprite.events.onDragStart.add( onDragStart, this );
				sprite.events.onDragStop.add( onDragStop, this );
			}

			function makeClickable( sprite ) {
				sprite.inputEnabled = true;
				sprite.input.useHandCursor = true;
				sprite.events.onInputDown.add( function () {


				}, this );

			}

			function onDragStart( sprite ) {
				//console.log(sprite);
			}

			function onDragStop( sprite ) {

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



		

			function startGame() {


				//				game.physics.startSystem( Phaser.Physics.ARCADE );


				//				cursors = game.input.keyboard.createCursorKeys(); // we need some cursor keys for our controls


				
					sceneLoaded = true;
					gameActive = true;


				//game.world.bringToTop( logoLayer );
				game.world.bringToTop( gwnLayer );

			}


			function update() {


				if ( gameActive ) {




					mx = game.input.x;
					my = game.input.y;

					

					if ( game.input.activePointer.isDown ) {



					} else {

					}

					omx = mx;
					omy = my;

				}
			}

			function render() {
				//game.debug.inputInfo(32, 32);
				if ( gameActive ) {
					//	game.debug.text( "Pies thrown: " + pieCount, 32, 32 );
					//	game.debug.text( "Max pies: " + pieCountMax, 32, 48 );
					//	game.debug.text( "HIT: " + hit, 32, 64 );
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
				//arrow.visible = false;


				var button = game.add.sprite( u, v + 200, 'button_continue' );
				button.anchor.setTo( 0.5, 0.5 );
				button.alpha = 0;
				var tween_button_alpha = game.add.tween( button ).to( {
					alpha: 1
				}, 500, Phaser.Easing.Linear.None, true );
				button.inputEnabled = true;
				button.input.useHandCursor = true;
				button.events.onInputUp.add( gotoIW, this );


				if ( userName == "" ) {
					winText = winText.replace( "<userName>", "" );
				} else {
					winText = winText.replace( "<userName>", ", " + userName.split( " " )[ 0 ] );
				}
				var text = game.add.text( u, v + 80, winText );
				text.anchor.set( 0.5 );
				text.align = 'center';
				text.font = 'Arial';
				text.fontWeight = 'bold';
				text.fontSize = 24;
				text.fill = '#ffffff';
				text.wordWrap = true;
				text.wordWrapWidth = 600;
				text.setShadow( 1, 1, 'rgba(0,0,0,0.8)', 3 );

				text.stroke = '#000000';
				text.strokeThickness = 2;
				text.fill = '#ffffff';
				text.alpha = 0;
				var text_button_alpha = game.add.tween( text ).to( {
					alpha: 1
				}, 500, Phaser.Easing.Linear.None, true );



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



	<script type="text/javascript">
		// GOOGLE ANALYTICS
		( function () {
			var ga = document.createElement( 'script' );
			ga.type = 'text/javascript';
			ga.async = true;
			ga.src = ( 'https:' == document.location.protocol ? 'https://ssl' : 'http://www' ) + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName( 'script' )[ 0 ];
			s.parentNode.insertBefore( ga, s );
		} )
		();
	</script>
	<script type="text/javascript">
		// GOOGLE ANALYTICS 
		var _gaq = _gaq || [];
		_gaq.push( [ '_setAccount', 'UA-4102833-1' ] ); //Must be placed prior to page view tracker
		_gaq.push( [ '_setDomainName', '.pch.com' ] ); //Must be placed prior to page view tracker
		_gaq.push( [ '_setAllowLinker', true ] ); //Must be placed prior to page view tracker
		_gaq.push( [ '_trackPageview', '/social_FB_InstantWin/desktop' ] );
		_gaq.push( [ '_set', 'campaignParams', 'utm_source=' + getURLValue( 'utm_source' ) + '&utm_medium=' + getURLValue( 'utm_medium' ) + '&utm_campaign=' + getURLValue( 'utm_campaign' ) ] );

		function gaEvent( eventCategory, eventAction, eventLabel ) {
			_gaq.push( [ '_trackEvent', eventCategory, eventAction, eventLabel ] );
		}

		function gaPage( pageName ) {
			// virtual page view
			_gaq.push(
				[ '_trackPageview', pageName ]
			);
		}
	</script>


</body>
</html>