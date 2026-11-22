<html>
<head><title>Oblique Strategies</title>
<script>
strategies = [
"<div class=strategy>(Organic) machinery</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>A line has two sides</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>A very small object&#8212;Its centre</div><div class=edition>(2)</div>",
"<div class=strategy>Abandon desire</div><div class=edition>(4)</div>",
"<div class=strategy>Abandon normal instructions</div><div class=edition>(4)</div>",
"<div class=strategy>Abandon normal instruments</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Accept advice</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>Accretion</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Adding on</div><div class=edition>(4)</div>",
"<div class=strategy>Allow an easement (an easement is the abandonment of a stricture)</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Always first steps</div><div class=edition>(2, 3)</div>",
"<div class=strategy>Always give yourself credit for having more than personality</div><div class=edition><i>given by Arto Lindsay</i>&nbsp;(2)</div>",
"<div class=strategy>Always the first steps</div><div class=edition>(4)</div>",
"<div class=strategy>Are there sections?  Consider transitions</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Ask people to work against their better judgement</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>Ask your body</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>Assemble some of the elements in a group and treat the group</div><div class=edition>(3)</div>",
"<div class=strategy>Assemble some of the instruments in a group and treat the group</div><div class=edition>(1, 2)</div>",
"<div class=strategy>Balance the consistency principle with the inconsistency principle</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Be dirty</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>Be extravagant</div><div class=edition>(2&#8211;4)</div>",
"<div class=strategy>Be less critical</div><div class=edition>(4)</div>",
"<div class=strategy>Be less critical more often</div><div class=edition>(3)</div>",
"<div class=strategy>Breathe more deeply</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>Bridges<br>&#8212;build<br>&#8212;burn</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>Cascades</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Change ambiguities to specifics</div><div class=edition>(4)</div>",
"<div class=strategy>Change instrument roles</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Change nothing and continue consistently</div><div class=edition>(4)</div>",
"<div class=strategy>Change nothing and continue with immaculate consistency</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Change specifics to ambiguities</div><div class=edition>(4)</div>",
"<div class=strategy>Children<br>&#8212;speaking<br>&#8212;singing</div><div class=edition>(3)</div>",
"<div class=strategy>Children&#8217;s voices<br>&#8212;speaking<br>&#8212;singing</div><div class=edition>(1, 2)</div>",
"<div class=strategy>Cluster analysis</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Consider different fading systems</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Consider transitions</div><div class=edition>(4)</div>",
"<div class=strategy>Consult other sources<br>&#8212;promising<br>&#8212;unpromising</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Convert a melodic element into a rhythmic element</div><div class=edition>(1, 2)</div>",
"<div class=strategy>Courage!</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>Cut a vital connection</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>Decorate, decorate</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>Define an area as &#8220;safe&#8221; and use it as an anchor</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Destroy<br>&#8212;nothing<br>&#8212;the most important thing</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Destroy nothing; Destroy the most important thing</div><div class=edition>(4)</div>",
"<div class=strategy>Discard an axiom</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>Disciplined self-indulgence</div><div class=edition>(2&#8211;4)</div>",
"<div class=strategy>Disconnect from desire</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Discover the recipes you are using and abandon them</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Discover your formulas and abandon them</div><div class=edition>(4)</div>",
"<div class=strategy>Display your talent</div><div class=edition>(4)</div>",
"<div class=strategy>Distort time</div><div class=edition>(4)</div>",
"<div class=strategy>Distorting time</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Do nothing for as long as possible</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>Do something boring</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>Do something sudden, destructive and unpredictable</div><div class=edition>(4)</div>",
"<div class=strategy>Do the last thing first</div><div class=edition>(4)</div>",
"<div class=strategy>Do the washing up</div><div class=edition>(1, 2)</div>",
"<div class=strategy>Do the words need changing?</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>Do we need holes?</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Don&#8217;t avoid what is easy</div><div class=edition>(4)</div>",
"<div class=strategy>Don&#8217;t be afraid of things because they&#8217;re easy to do</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Don&#8217;t be frightened of cliches</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Don&#8217;t be frightened to display your talents</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Don&#8217;t break the silence</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>Don&#8217;t stress on thing more than another <small><i>(sic)</i></small></div><div class=edition>(2)</div>",
"<div class=strategy>Don&#8217;t stress one thing more than another</div><div class=edition>(1, 3, 4)</div>",
"<div class=strategy>Emphasize differences</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>Emphasize repetitions</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Emphasize the flaws</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>Faced with a choice, do both</div><div class=edition><i>from Dieter Rot</i>&nbsp;(4)</div>",
"<div class=strategy>Faced with a choice, do both</div><div class=edition><i>given by Dieter Rot</i>&nbsp;(1, 2)</div>",
"<div class=strategy>Feed the recording back out of the medium</div><div class=edition>(2)</div>",
"<div class=strategy>Feedback recordings into an acoustic situation</div><div class=edition>(1)</div>",
"<div class=strategy>Fill every beat with something</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Find a safe part and use it as an anchor</div><div class=edition>(4)</div>",
"<div class=strategy>From nothing to more than nothing</div><div class=edition>(3)</div>",
"<div class=strategy>Get your neck massaged</div><div class=edition>(1, 2)</div>",
"<div class=strategy>Ghost echoes</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Give the game away</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>Give way to your worst impulse</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>Go outside.  Shut the door.</div><div class=edition>(2&#8211;4)</div>",
"<div class=strategy>Go slowly all the way round the outside</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Go to an extreme, come part way back</div><div class=edition>(4)</div>",
"<div class=strategy>Go to an extreme, move back to a more comfortable place</div><div class=edition>(3)</div>",
"<div class=strategy>Honor thy error as a hidden intention</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>How would someone else do it?</div><div class=edition>(4)</div>",
"<div class=strategy>How would you have done it?</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>Humanize something free of error</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Idiot glee (?)</div><div class=edition>(2, 3)</div>",
"<div class=strategy>Imagine the music as a moving chain or caterpillar</div><div class=edition>(1)</div>",
"<div class=strategy>Imagine the music as a set of disconnected events</div><div class=edition>(1)</div>",
"<div class=strategy>Imagine the piece as a set of disconnected events</div><div class=edition>(2, 3)</div>",
"<div class=strategy>In total darkness, or in a very large room, very quietly</div><div class=edition>(2&#8211;4)</div>",
"<div class=strategy>Infinitesimal gradations</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Intentions<br>&#8212;credibility of<br>&#8212;nobility of<br>&#8212;humility of</div><div class=edition>(1, 2)</div>",
"<div class=strategy>Intentions<br>&#8212;nobility of<br>&#8212;humility of<br>&#8212;credibility of</div><div class=edition>(3)</div>",
"<div class=strategy>Into the impossible</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Is it finished?</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>Is something missing?</div><div class=edition>(4)</div>",
"<div class=strategy>Is the intonation correct?</div><div class=edition>(2, 3)</div>",
"<div class=strategy>Is the style right?</div><div class=edition>(4)</div>",
"<div class=strategy>Is the tuning appropriate?</div><div class=edition>(1)</div>",
"<div class=strategy>Is there something missing?</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>It is quite possible (after all)</div><div class=edition;(2, 3)</div>",
"<div class=strategy>It is simply a matter of work</div><div class=edition>(4)</div>",
"<div class=strategy>Just carry on</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>Left channel, right channel, centre channel</div><div class=edition>(1, 2)</div>",
"<div class=strategy>Listen in total darkness, or in a very large room, very quietly</div><div class=edition>(1)</div>",
"<div class=strategy>Listen to the quiet voice</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>Look at a very small object, look at its centre</div><div class=edition>(1)</div>",
"<div class=strategy>Look at the order in which you do things</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>Look closely at the most embarrassing details and amplify them</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Lost in useless territory</div><div class=edition>(2, 3)</div>",
"<div class=strategy>Lowest common denominator</div><div class=edition>(2, 3)</div>",
"<div class=strategy>Lowest common denominator check<br>&#8212;single beat<br>&#8212;single note<br>&#8212;single riff</div><div class=edition>(1)</div>",
"<div class=strategy>Magnify the most difficult details</div><div class=edition>(4)</div>",
"<div class=strategy>Make a blank valuable by putting it in an exquisite frame</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Make a sudden, destructive unpredictable action; incorporate</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Make an exhaustive list of everything you might do and do the last thing on the list</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Make it more sensual</div><div class=edition>(4)</div>",
"<div class=strategy>Make what&#8217;s perfect more human</div><div class=edition>(4)</div>",
"<div class=strategy>Mechanicalize something idiosyncratic</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Move towards the unimportant</div><div class=edition>(4)</div>",
"<div class=strategy>Mute and continue</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Not building a wall but making a brick</div><div class=edition>(2, 3)</div>",
"<div class=strategy>Not building a wall; making a brick</div><div class=edition>(4)</div>",
"<div class=strategy>Once the search has begun, something will be found</div><div class=edition>(4)</div>",
"<div class=strategy>Once the search is in progress, something will be found</div><div class=edition>(3)</div>",
"<div class=strategy>Only a part, not the whole</div><div class=edition>(3, 4)</div>",
"<div class=strategy>Only one element of each kind</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>Openly resist change</div><div class=edition>(4)</div>",
"<div class=strategy>Overtly resist change</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Put in earplugs</div><div class=edition>(1, 2)</div>",
"<div class=strategy>Question the heroic</div><div class=edition>(4)</div>",
"<div class=strategy>Question the heroic approach</div><div class=edition>(2, 3)</div>",
"<div class=strategy>Remember those quiet evenings</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Remember quiet evenings</div><div class=edition>(4)</div>",
"<div class=strategy>Remove a restriction</div><div class=edition>(4)</div>",
"<div class=strategy>Remove ambiguities and convert to specifics</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Remove specifics and convert to ambiguities</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Repetition is a form of change</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>Retrace your steps</div><div class=edition>(3, 4)</div>",
"<div class=strategy>Revaluation (a warm feeling)</div><div class=edition>(2, 3)</div>",
"<div class=strategy>Reverse</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>Short circuit <small>(example: a man eating peas with the idea that they will improve his virility shovels them straight into his lap)</small></div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Shut the door and listen from outside</div><div class=edition>(1)</div>",
"<div class=strategy>Simple subtraction</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>Simply a matter of work</div><div class=edition>(2, 3)</div>",
"<div class=strategy>Slow preparation, fast execution</div><div class=edition>(4)</div>",
"<div class=strategy>Spectrum analysis</div><div class=edition>(1, 2)</div>",
"<div class=strategy>State the problem as clearly as possible</div><div class=edition>(4)</div>",
"<div class=strategy>State the problem in words as clearly as possible</div><div class=edition>(2, 3)</div>",
"<div class=strategy>Take a break</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>Take away the elements in order of apparent non-importance</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Take away the important parts</div><div class=edition>(4)</div>",
"<div class=strategy>Tape your mouth</div><div class=edition><i>given by Ritva Saarikko</i> (1, 2)</div>",
"<div class=strategy>The inconsistency principle</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>The most easily forgotten thing is the most important</div><div class=edition>(4)</div>",
"<div class=strategy>The most important thing is the thing most easily forgotten</div><div class=edition>(2, 3)</div>",
"<div class=strategy>The tape is now the music</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Think<br>&#8212;inside the work<br>&#8212;outside the work</div><div class=edition>(4)</div>",
"<div class=strategy>Think of the radio</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Tidy up</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>Towards the insignificant</div><div class=edition>(2, 3)</div>",
"<div class=strategy>Trust in the you of now</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Try faking it</div><div class=edition><i>from Stewart Brand</i>&nbsp;(4, diary 8/9)</div>",
"<div class=strategy>Turn it upside down</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>Twist the spine</div><div class=edition>(1, 2)</div>",
"<div class=strategy>Use an old idea</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>Use an unacceptable colour</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Use cliches</div><div class=edition>(4)</div>",
"<div class=strategy>Use fewer notes</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Use filters</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>Use something nearby as a model</div><div class=edition>(4)</div>",
"<div class=strategy>Use your own ideas</div><div class=edition>(4)</div>",
"<div class=strategy>Use &#8220;unqualified&#8221; people</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>Voice your suspicions</div><div class=edition>(4)</div>",
"<div class=strategy>Water</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>What are the sections sections of? Imagine a caterpillar moving</div><div class=edition>(2, 3)</div>",
"<div class=strategy>What context would look right?</div><div class=edition>(4)</div>",
"<div class=strategy>What is the reality of the situation?</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>What is the simplest solution?</div><div class=edition>(4)</div>",
"<div class=strategy>What mistakes did you make last time?</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>What to increase? What to reduce? What to maintain?</div><div class=edition>(4)</div>",
"<div class=strategy>What would your closest friend do?</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>What wouldn&#8217;t you do?</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>When is it for?</div><div class=edition>(4)</div>",
"<div class=strategy>Where is the edge?</div><div class=edition>(4)</div>",
"<div class=strategy>Which parts can be grouped?</div><div class=edition>(4)</div>",
"<div class=strategy>Work at a different speed</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>Would anybody want it?</div><div class=edition>(3)</div>",
"<div class=strategy>Would anyone want it?</div><div class=edition>(4)</div>",
"<div class=strategy>You are an engineer</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>You can only make one dot at a time</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>You don&#8217;t have to be ashamed of using your own ideas</div><div class=edition>(1&#8211;3)</div>",
"<div class=strategy>Your mistake was a hidden intention</div><div class=edition>(4)</div>",
"<div class=strategy>[blank card]</div><div class=edition>(1&#8211;4)</div>",
"<div class=strategy>Steal a solution.</div><div class=edition>(diary 7/22)</div>",
"<div class=strategy>Describe the landscape in which this belongs.</div><div class=edition>(diary 8/9)</div>",
"<div class=strategy>What else is this like?</div><div class=edition>(diary 8/9)</div>",
"<div class=strategy>List the qualities it has. List those you&#8217;d like.</div><div class=edition>(diary 8/9)</div>",
"<div class=strategy>Instead of changing the thing, change the world around it.</div><div class=edition>(diary 8/9)</div>",
"<div class=strategy>What would make this really successful?</div><div class=edition>(diary 8/9)</div>",
"<div class=strategy>How would you explain this to your parents?</div><div class=edition>(diary 8/9)</div>",
"<div class=strategy>What were the branch points in the evolution of this entity?</div><div class=edition>(diary 8/20)</div>",
"<div class=strategy>Back up a few steps. What else could you have done?</div><div class=edition>(diary 8/20)</div>",
"<div class=strategy>When is it for? Who is it for?</div><div class=edition>(diary 8/23)</div>",
"<div class=strategy>What do you do? Now, what do you do best?</div><div class=edition>(diary 8/27)</div>",
"<div class=strategy>First work alone, then work in unusual pairs.</div><div class=edition>(diary 9/8)</div>",
"<div class=strategy>What most recently impressed you? How is it similar? What can you learn from it? What could you take from it?</div><div class=edition>(diary 9/10)</div>",
"<div class=strategy>Take away as much mystery as possible. What is left?</div><div class=edition>(diary 12/30)"
];
</script>
<style type=text/css>
.strategy {
	font-family: verdana, arial, helvetica;
	font-size: 14px;
	font-weight: bold;
	color: black;
	text-align:left;
	height: 100;
}
.edition {
	font-family: verdana, arial, helvetica;
	font-size: 10px;
	color: gray;
	text-align:right;
}
body {
	background-color: white;
	margin: 16px;
}
a {
	color: cccccc;
}
hr {
	color: 999999;
	width: 100%;
	height: 1px;
}
</style>

<meta name="viewport" content="width=320" />
<meta name="apple-mobile-web-app-capable" content="yes" />

</head>
<body>

<table width="100%" cellpadding=0 cellspacing=0 border=0>
<tr><td valign=top id="strategy">
</td></tr>
<tr><td valign=bottom align=center>
<form>
<img src="images/pixel333333.gif" width="100%" height=1 vspace=8><br>
<input type=button value="Again" onClick="document.getElementById('strategy').innerHTML = strategies[Math.floor(Math.random()*strategies.length)]"></form>
</td></tr></table>
<script>
	document.getElementById('strategy').innerHTML = strategies[Math.floor(Math.random()*strategies.length)]
</script>
</body>
</html>