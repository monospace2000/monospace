<?php 
/* Short and sweet */
/*
define('WP_USE_THEMES', true);
require('./wordpress/wp-blog-header.php');
*/
?>

<html>
	<head><title>monospace</title>


	<meta name="viewport" content="width=700">

	<style>
		body{
			margin: 0;
			font-family: monospace;
			background: 	url("images/logo_xl_transp.png") 50% 30vh no-repeat, 
					radial-gradient(circle at 50% 33vh, rgba(0, 0, 255, 0.75) 0%, rgba(0, 0, 255, 0) 33vw) no-repeat, 
					rgba(0, 0, 0, 1);
			background-size: 67%, 100%, 100%;
			color: rgba(255, 255, 255, 1);	
			text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.5);
		}
		a {
			color: rgba(255, 255, 255, 1);
			text-decoration:none;
		}




		ul{
			width: 50%;
			height: auto;
			margin: 100px auto 0;
			list-style-type: none;
			padding: 0;
		}
		li{
			font-size: 12px;
		}
		li a{
			font-size: 24px;
		}

		.badge{
			width: calc(768px / 4);
			height: calc(733px /4 );
			background: url("images/25years.png") center no-repeat;
			background-size: cover;
			position: absolute;
			transform-origin: 50% 50%;
			left: 45vw;
			top: 50vh;
		}
		.tag{
			font-family: "Palatino";
			font-size: 24pt;
			text-align: center;
			font-style: italic;
			color: rgba(255, 255, 255, 0.7);
			margin-top: 50vh;
			text-shadow: 1px 4px 4px rgba(0, 0, 0, 1);
		}

		.menu{
			display: none;
			
		}

		


		.link{
			width: 50%;
			text-align: center;
			margin: 10% auto 0;
			color: white;
		}
	</style>

</head>

<body>


<div class="tag">You are visitor number one.<sup style="font-size: 30%">TM</sup></div>




</body>
</html>

