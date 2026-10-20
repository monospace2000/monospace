<?php
	//echo isset($_COOKIE['pch_gb2013']);
	//$midnight = date(strtotime('midnight'));
	//echo date('r', $midnight);
	date_default_timezone_set('EST');
	echo setcookie('pch_fb_iw', $_GET['value'], mktime(22, 59, 59, date("m"), date("d"), date("y")), "/", "pch.com");
	//echo setcookie('pch_fb_iw', $_GET['value'], $_GET['expires']);
	//echo $_COOKIE['pch_gb2013'];
?>