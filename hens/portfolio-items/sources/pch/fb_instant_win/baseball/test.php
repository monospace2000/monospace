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
			document.location.href = "already_played.php"
		}
	</script>




	<script>
		// modify these as needed:
		var introText = "INTRODUCTION";
		var winText = 'GAME OVER';
		var giveawayNumbers = "Gwy. Nos. 10184, 13450, 13451, 3452, 13453";

		
		var deviceID = "13244"; //desktop
		//var deviceID = "13247"; //mobile
		var instantWinURL = "http://www.pch.com/gameplay/mini-game/facebook-monthly-game?utm_source=facebook&utm_medium=pchcom_fb_pch&utm_campaign=41_facebookexclusive&tsrc=pchcom_fb_pch&tsrc2=41_facebookexclusive";


		///// DO NOT MODIFY BELOW ////////////////////////////////////////////////////



		window.onload = function () {

			var game = new Phaser.Game( 820, 640, Phaser.CANVAS, '', {
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
			var SPEED = 2;
			var KEYDOWN = false;

			var leaves = [];
			var maxLeaves = 800;
			var displacement = 0.5;

			var cutLeaves = 0;

			var cutDistance = 100;
			var baseGravity = 400;
			
			var backgroundScale = 0.6;
			
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

				game.load.image( 'soil', 'images/soil.png?' + cb );
				game.load.image( 'sign', 'images/sign.png?' + cb );
				game.load.image( 'sign_background', 'images/sign_background.png?' + cb );
				game.load.image( 'flowers', 'images/flowers.png?' + cb );

				game.load.image( 'intro', 'images/intro.png?' + cb );
				game.load.image( 'outro', 'images/outro.png?' + cb );
				game.load.image( 'instructions', 'images/instructions.png?' + cb );				
				game.load.image( 'background', 'images/background.png?' + cb );

				game.load.atlas( 'plant1', 'images/plant1.png?' + cb, 'images/plant1.json?' + cb );
				game.load.atlas( 'plant2', 'images/plant2.png?' + cb, 'images/plant2.json?' + cb );
				game.load.atlas( 'plant3', 'images/plant3.png?' + cb, 'images/plant3.json?' + cb );

				game.load.spritesheet('preloader', 'images/preloader.png', 64, 64, 12);
				
				game.load.atlas( 'shears', 'images/shears.png?' + cb, 'images/shears.json?' + cb );

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

				//blurX = game.add.filter( 'BlurX' );
				//blurY = game.add.filter( 'BlurY' );
				//blurX.blur = 0;
				//blurY.blur = 0;


				/////////////////////////////////////////////////

				background = game.add.sprite(0, 0, 'background');

				tmp_shears = game.add.sprite(u, v-150, 'shears')
				tmp_shears.anchor.setTo(0.5);

				logo = game.add.sprite( u, 40, "logo_white" );
				logo.anchor.setTo( 0.5, 0.5 );
				logo.fixedToCamera = true;
				logoLayer = game.add.group();
				logoLayer.add( logo );

				title = game.add.sprite( u, v - 125, "title" );
				title.anchor.setTo( 0.5, 0.5 );

				sash = game.add.sprite( 0, 0, "sash" );




/*
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
*/
				intro = game.add.sprite(u, v+50, 'intro');
				intro.anchor.setTo(0.5);


				startButton = game.add.sprite( u, v + 225, 'button_playnow' )
				startButton.anchor.setTo( 0.5, 0.5 );
				startButton.inputEnabled = true;
				startButton.input.useHandCursor = true;
				startButton.events.onInputUp.add( function () {

					preloader = game.add.sprite(u, v, 'preloader');
					preloader.anchor.setTo(0.5);
					preloader.animations.add('spin');
					preloader.animations.play('spin', 50, true);

					var tween_sash = game.add.tween( sash ).to( {
						alpha: 0
					}, 500, Phaser.Easing.Linear.None, true );
					var tween_background = game.add.tween( background ).to( {
						alpha: 0
					}, 500, Phaser.Easing.Linear.None, true );
					var tween_tmp_shears = game.add.tween( tmp_shears ).to( {
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
						setTimeout(initGame, 500);
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
				console.log( "x: " + ( duck_head.x - duck_body.x ) + ", y:" + ( duck_head.y - duck_body.y ) )
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

			
				
				backgroundLayer = game.add.group();
				
				background = game.add.sprite( 0, 0, 'sign_background' );
				//background.scale.setTo( backgroundScale);
				//background.anchor.setTo( 0.5 );

				sign = game.add.sprite( background.x+402, background.y+449, 'sign' );
				//sign.scale.setTo( backgroundScale );
				//sign.anchor.setTo( 0.5 );

				flowers = game.add.sprite( background.x+102, background.y+1063, 'flowers' );
				//flowers.scale.setTo( backgroundScale );
				//flowers.anchor.setTo( 0.5 );

				backgroundLayer.add(background);
				backgroundLayer.add(sign);
				backgroundLayer.add(flowers);
				backgroundLayer.scale.setTo(backgroundScale);
				
				backgroundLayer.x = u-(background.width/2)*backgroundScale;
				backgroundLayer.y = v-(background.height/2)*backgroundScale;
				
			

				game.physics.startSystem( Phaser.Physics.ARCADE );
				game.physics.arcade.gravity.y = 100;

				leavesLayers = [];
				leavesLayer1 = game.add.group();
				leavesLayers.push( leavesLayer1 );
				leavesLayer2 = game.add.group();
				leavesLayers.push( leavesLayer2 );
				leavesLayer3 = game.add.group();
				leavesLayers.push( leavesLayer3 );
				leavesLayer4 = game.add.group();
				leavesLayers.push( leavesLayer4 );
				leavesLayer5 = game.add.group();
				leavesLayers.push( leavesLayer5 );

				color = 0;
				colorStep = 255 / maxLeaves;

				maxBlur = 20;
				blurValue = 0;
				blurStep = maxBlur / ( maxLeaves / 3 );


				for ( var i = 0; i < maxLeaves; i++ ) {
					leaves[ i ] = game.add.sprite( game.rnd.between( -50, right+50 ), game.rnd.between( -50, bottom+50 ), 'plant' + game.rnd.between( 1, 3 ) )
						//leaves[i] = game.add.sprite(i, v, 'plant1')
					leaves[ i ].id = i;
					leaves[ i ].cut = false;
					leaves[ i ].anchor.setTo( 0.5 );
					//leaves[ i ].scale.setTo( 0.5 );
					leaves[ i ].angle = game.rnd.between( 0, 360 );
					leaves[ i ].frame = game.rnd.between( 0, 23 );



					// set color tint
					color = Math.round( i * colorStep ).toString( 16 );
					if ( color.length == 1 ) color = "0" + color;
					hex = parseInt( "" + color + color + color, 16 );
					leaves[ i ].tint = hex;




					// distribute to layers
					if ( i < maxLeaves * ( 1 / leavesLayers.length ) ) {
						leavesLayer1.add( leaves[ i ] );
					} else if ( i < maxLeaves * .4 && i > maxLeaves * .2 ) {
						leavesLayer2.add( leaves[ i ] );
					} else if ( i < maxLeaves * .6 && i > maxLeaves * .4 ) {
						leavesLayer3.add( leaves[ i ] );
					} else if ( i < maxLeaves * .8 && i > maxLeaves * .6 ) {
						leavesLayer4.add( leaves[ i ] );
					} else {
						leavesLayer5.add( leaves[ i ] );
					}


				}

				//game.physics.enable( leaves, Phaser.Physics.ARCADE);



				// blur layers
				//				blurX.blur = maxBlur;
				//				blurY.blur = maxBlur;
				//				leavesLayer1.filters = [ blurX, blurY ]
				//
				//				blurX.blur = maxBlur*.75;
				//				blurY.blur = maxBlur*.75;
				//				leavesLayer2.filters = [ blurX, blurY ]
				//
				//				blurX.blur = maxBlur*.5;
				//				blurY.blur = maxBlur*.5;
				//				leavesLayer3.filters = [ blurX, blurY ]
				//
				//				blurX.blur = maxBlur*.25;
				//				blurY.blur = maxBlur*.25;
				//				leavesLayer4.filters = [ blurX, blurY ]


				shears = game.add.sprite( u, v, 'shears' );
//				shears.scale.setTo( 0.5 );
				shears.anchor.setTo( 0.3, 0.75 );
				shears.animations.add( 'snip', Phaser.Animation.generateFrameNames( 'shears', 0, 10, "", 4 ), 30, false );
				shears.inputEnabled = true;
				shears.input.useHandCursor = true;
				shears.events.onInputDown.add( snip, this );

				
				instructions = game.add.sprite(u, v-100, 'instructions');
				instructions.anchor.setTo(0.5);

				startGame();

			}



			function startGame() {

				game.world.bringToTop( logoLayer );
				game.world.bringToTop( gwnLayer )
				gameActive = true;
			}


			function snip() {
				if (instructions.alpha == 1){		
					var tween_instructions = game.add.tween( instructions ).to( {
						alpha: 0
					}, 500, Phaser.Easing.Linear.None, true );
				}
				shears.animations.play( 'snip' );

				for ( var i = 0; i < maxLeaves; i++ ) {
					if ( game.physics.arcade.distanceToPointer( leaves[ i ] ) < cutDistance && leaves[ i ].cut == false ) {
						cutLeaves++;
						leaves[ i ].cut = true;
						
						game.physics.enable( leaves[ i ], Phaser.Physics.ARCADE );
						leaves[ i ].body.gravity.y = baseGravity + game.rnd.between( -baseGravity / 4, baseGravity / 4 );
	
						leaves[i].checkWorldBounds = true;
            			leaves[i].events.onOutOfBounds.add(removeLeaf, this);
						
						leaves[ i ].body.allowGravity = true;
						leaves[ i ].body.velocity.y = 400 + game.rnd.between( -50, 50 );
						leaves[ i ].body.velocity.x = game.rnd.between( -100, 100 );
						if ( Math.random() > Math.random() ) {
							leaves[ i ].body.angularAcceleration -= 200;
						} else {
							leaves[ i ].body.angularAcceleration += 200;
						}
					}
				}
				
				if (cutLeaves/maxLeaves>0.67){
					for ( var i = 0; i < maxLeaves; i++ ) {
						if ( leaves[ i ].cut == false ) {
							cutLeaves++;
							leaves[ i ].cut = true;

							game.physics.enable( leaves[ i ], Phaser.Physics.ARCADE );
							leaves[ i ].body.gravity.y = baseGravity + game.rnd.between( -baseGravity / 4, baseGravity / 4 );

							leaves[i].checkWorldBounds = true;
							leaves[i].events.onOutOfBounds.add(removeLeaf, this);

							leaves[ i ].body.allowGravity = true;
							leaves[ i ].body.velocity.y = 400 + game.rnd.between( -50, 50 );
							leaves[ i ].body.velocity.x = game.rnd.between( -100, 100 );
							if ( Math.random() > Math.random() ) {
								leaves[ i ].body.angularAcceleration -= 200;
							} else {
								leaves[ i ].body.angularAcceleration += 200;
							}
						}
					}
					shearsAlpha = game.add.tween( shears ).to( {
						alpha: 0
					}, 500, Phaser.Easing.Linear.None, true );
					endGame();
				}
				
				
			}
			
			function removeLeaf(sprite){
				sprite.body = null;
				sprite.destroy();
			}

			function update() {

				if ( sceneLoaded ) {

				}


				if ( gameActive ) {


					mx = game.input.x;
					my = game.input.y;
					
					shears.x = mx;
					shears.y = my;

					dx = u - mx;
					dy = v - my;

					/*
					leavesLayer1 . x = dx * displacement * .02;
					leavesLayer1 . y = dy * displacement * .02;
					leavesLayer2 . x = dx * displacement * .04;
					leavesLayer2 . y = dy * displacement * .04;
					leavesLayer3 . x = dx * displacement * .06;
					leavesLayer3 . y = dy * displacement * .06;
					leavesLayer4 . x = dx * displacement * .08;
					leavesLayer4 . y = dy * displacement * .08;
					leavesLayer5 . x = dx * displacement * .1;
					leavesLayer5 . y = dy * displacement * .1;
					*/

					/*
					spinner.body.angularAcceleration = 0; // reset acceleration every update cycle

					if ( game.input.keyboard.isDown( Phaser.Keyboard.RIGHT ) ) {
						KEYDOWN = true;
						spinner.body.angularAcceleration += 200;
					} else {
						KEYDOWN = false;
					}

					if (KEYDOWN ) spinner.body.angularAcceleration += 200;
					SPEED = spinner.body.deltaZ();
					*/

					if ( game.input.activePointer.isDown ) {




						/*
						q = game.rnd.between( 0, maxLeaves - 21 );
						for ( var i = q; i < q + 20; i++ ) {
							//leaves[i].visible = false
							leaves[ i ].body.allowGravity = true;
							if ( Math.random() > Math.random() ) {
								leaves[i].body.angularAcceleration -= 200;
							} else {
								leaves[i].body.angularAcceleration += 200;
							}
						}
						*/
					}





					omx = mx;
					omy = my;

				}

			}





			function render() {
				//game.debug.inputInfo(32, 32);
				//game.debug.text( "Game Active: " + gameActive, 32, 32 );
				if ( gameActive ) {

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
					
					var button = game.add.sprite( u, v + 225, 'button_continue' );
					button.anchor.setTo( 0.5, 0.5 );
					button.alpha = 0;
					var tween_button_alpha = game.add.tween( button ).to( {
						alpha: 1
					}, 500, Phaser.Easing.Linear.None, true );
					button.inputEnabled = true;
					button.input.useHandCursor = true;
					button.events.onInputUp.add( gotoIW, this );
					
					outro = game.add.sprite(u, v-100, 'outro');
					outro.anchor.setTo(0.5);

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