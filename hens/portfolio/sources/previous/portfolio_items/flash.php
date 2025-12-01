<html>
<head>
<title>Hens Breet: Portfolio: <?=$title?>
</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type=text/css>
body {
	margin: 0px;
}
</style>

<script type="text/javascript">
function $() {
  var elements = new Array();

  for (var i = 0; i < arguments.length; i++) {
    var element = arguments[i];
    if (typeof element == 'string')
      element = document.getElementById(element);

    if (arguments.length == 1)
      return element;

    elements.push(element);
  }

  return elements;
}
function getURLValue(v){
	q = document.location.search.substring(1);
	if (q=="") return q;
	pairs = q.split("&");
	for (i=0; i<pairs.length; i++){
		if (pairs[i].split("=")[0].toLowerCase() == v.toLowerCase()) return unescape(pairs[i].split("=")[1]);
	}
	return "";
}
window.document.title =  "Hens Breet: Portfolio: " + getURLValue("title");
</script>
<script type="text/javascript" src="../swfobject/swfobject.js"></script>
<script type="text/javascript">

swfobject.embedSWF(getURLValue("source"), "myContent", getURLValue("width"), getURLValue("height"), "9.0.0", "../swfobject/expressInstall.swf");
</script>


</head>

<body>

<div id="myContent">
	<h1>Alternative content</h1>
	<p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></p>
</div>
		


</body>
</html>
