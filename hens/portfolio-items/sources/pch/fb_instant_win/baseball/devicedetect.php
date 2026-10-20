<!doctype html>
<html>
<head>

<title>Redirecting...</title>
</head>

<body>
<script type="text/javascript" src="js/jquery-1.6.4.min.js"></script>
<script type="text/javascript" src="js/jquery.cookies.2.2.0.min.js"></script>

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


if ( $.cookies.get("pch_fb_iw")!=null){
	// already played	
	document.location.href = "already_played.php"
}else{
	// see if user is recognized on Spectrum:
	document.location.href = "https://fbapp.pch.com/app_register/registration.php"
}
</script>


<!-- OLD device detection 
<script src="https://scdn.pch.com/spectrum/mobile/js/pchDeviceDetection.v2.min.js"></script>
<script>
if (_pchDeviceType!="desktop"){
	top.location.href = "http://fbapp.pch.com/app_game_monte_may2016/index_mobile.php" + document.location.search;	
}else{
	top.location.href = "https://www.facebook.com/pch/app/676918965671465?app_data=" + encodeURIComponent(document.location.search.substring(1));
}
</script>-->






</body>
</html>