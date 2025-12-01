<html>
<head>
<title>m o n o s p a c e: dutch words</title>
<link rel=stylesheet href="monospace.css">
<style type="text/css">
<!--
body{
	color: 999999;
	background: url(images/glow.gif);
	background-repeat: no-repeat;
	background-attachment: fixed;
	background-color: black;
	font-family: "Lucida Grande", verdana, geneva, sans-serif; 
	font-size: 11px;
	margin: 32px;
}
.word {
	font-weight: bold;
}
a.word{
	color: white;
	text-decoration: none;
}
a.word:hover{
	text-decoration: underline;
}
.type {
	font-style: italic;
}
.trans {
	color: #999999;
}
.gray{
	color: gray;
}
-->
</style>
</head>
<body>

<a href="http://www.monospace.com/wayback"><img src="images/pixel.gif" width="391" height="51" border="0" vspace="16"></a>
						
						<br><br>
						<div align="center">
							<img src="images/visitorone.gif" width="245" height="15" border="0">
						</div>
						<img src="images/pixel.gif" width="32" height="32" border="0">



<p>The Dutch Word Of The Day is, well, just that: a Dutch word, carefully selected and expertly pronounced by yours truly, each day, every day, just for you. Why? Because I am Dutch, and Dutch is a peculiar language. 
<p>I picked these particular words because I think they either look or sound funny. There's no deeper meaning, expressed or implied.
<p>(BTW, the turnover for new words is midnight, New York time)
<div class=subhead>previous Dutch words</div>
<img src="images/pixel333333.gif" width="100%" height=1 vspace=8><br>

<?php
function parseMessage($message){
	$items = explode("\t", $message);
	$popup = "Click to hear the word '$items[0]' pronounced";
	$out = "<td class=word><a class=word title=\"$popup\" href=\"javascript:alert('$items[0].mp3')\">$items[0]</a></td>";
	$out .= "<td class=type>&nbsp;&nbsp;$items[1]&nbsp;</td>";
	$out .= "<td class=trans>$items[2]</td>";
	//$message = ereg_replace("<heart>", "<img src=\"images/smileys/icon-heart.gif\">", $message);	
	
	//convert URLs to hyperlinks:
	//$message = ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]", "<a href=\"\\0\">\\0</a>", $message);
	return $out;
}

$archive = file("dutch_words.txt");

$today = getdate();
$daynum = $today['yday'];

echo "<table>\n";
for ($i=0; $i<$daynum; $i++){
	echo "<tr valign=top>";
	$text = stripslashes( $archive[$i] );
	echo parseMessage($text);
	echo "</tr>\n";
} 
echo "</table>\n";

?>
</body>
</html>

