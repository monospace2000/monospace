<?php
function parseMessage($message){
	$items = explode("\t", $message);
	$popup = "Click to hear the word '$items[0]' pronounced";

	$out = "<td class=word><a class=word title=\"$popup\" href=\"javascript:doWord('$items[0]')\">$items[0]</a>&nbsp;&nbsp;&nbsp;</td>";
	$out .= "<td class=type><b>$items[1]</b>&nbsp;&nbsp;&nbsp;</td>";
	$out .= "<td class=trans>$items[2]</td>";

	return $out;
}

$archive = file("dutch_words.txt");
$today = getdate();
$daynum = $today['yday'];//-270;
echo "<table cellpadding=0 cellspacing=0 border=0>\n";
echo "<tr><td colspan=3 style='font-size:11px'>Dutch Word of the Day:<br><br></td></tr>";
echo "<tr valign=top>";
$text = stripslashes( $archive[$daynum] );
echo parseMessage($text);
echo "</tr>\n";
echo "</table>\n";
?>
