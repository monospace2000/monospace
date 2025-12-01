/*
monospace chord finder
(c)2003 monospace. all rights reserved.
re-use only with this notice intact.
*/


// images -------------------------------------------------------------------------

pix = new Array();
pix['bg0'] = new Image();
pix['bg0'].src = "images/grid0.gif";
pix['bg1'] = new Image();
pix['bg1'].src = "images/grid1.gif";
pix['-'] = new Image();
pix['-'].src = "images/transp.gif";
pix['x'] = new Image();
pix['x'].src = "images/cross.gif";
pix['o'] = new Image();
pix['o'].src = "images/circle.gif";
pix['*'] = new Image();
pix['*'].src = "images/dot.gif";
pix['='] = new Image();
pix['='].src = "images/span.gif";
pix['<'] = new Image();
pix['<'].src = "images/span_left.gif";
pix['>'] = new Image();
pix['>'].src = "images/span_right.gif";


// -----------------------------------------------------------------------------------------------

function showChord(){
	base = document.selector.key.options[document.selector.key.options.selectedIndex].value;
	shape = document.selector.variant.options[document.selector.variant.options.selectedIndex].value;
	chord = chords[base+shape];

	fretPos = chord.charAt(chord.length-1);
	if (fretPos>0){
		if (fretPos==1) fretPos=11;
		document.getElementById('chordsgrid').style.background="url(images/grid0.gif)";
		document.getElementById('fret').innerHTML = (fretPos==3)? fretPos+"rd": fretPos+"th";
	}else{
		document.getElementById('chordsgrid').style.background="url(images/grid1.gif)";
		document.getElementById('fret').innerHTML = "";
	}
	for (i=0; i<chord.length-1; i++){
		document.images[i].src = pix[chord.charAt(i)].src;
	}
}


// chord definitions ---------------------------------------------------------------

chords = new Array();

// C
chords['c_maj']  = '---o-o';
chords['c_maj'] += '----*-';
chords['c_maj'] += '--*---';
chords['c_maj'] += '**----';
chords['c_maj'] += '------';
chords['c_maj'] += '0'; // fret

chords['c_7']  = 'x----o';
chords['c_7'] += '----*-';
chords['c_7'] += '--*---';
chords['c_7'] += '-*-*--';
chords['c_7'] += '------';
chords['c_7'] += '0'; // fret

chords['c_6']  = 'x----o';
chords['c_6'] += '----*-';
chords['c_6'] += '--**--';
chords['c_6'] += '-*----';
chords['c_6'] += '------';
chords['c_6'] += '0'; // fret

chords['c_min']  = 'xx----';
chords['c_min'] += '-----*';
chords['c_min'] += '----*-';
chords['c_min'] += '--**--';
chords['c_min'] += '------';
chords['c_min'] += '3'; // fret

chords['c_min7']  = 'x--o-x';
chords['c_min7'] += '-**-*-';
chords['c_min7'] += '------';
chords['c_min7'] += '------';
chords['c_min7'] += '------';
chords['c_min7'] += '0'; // fret

chords['c_min6']  = 'xx----';
chords['c_min6'] += '--*-*-';
chords['c_min6'] += '---*--';
chords['c_min6'] += '-----*';
chords['c_min6'] += '------';
chords['c_min6'] += '0'; // fret

chords['c_aug']  = 'x----x';
chords['c_aug'] += '---**-';
chords['c_aug'] += '--*---';
chords['c_aug'] += '-*----';
chords['c_aug'] += '------';
chords['c_aug'] += '0'; // fret

chords['c_dim']  = 'xx----';
chords['c_dim'] += '--*-*-';
chords['c_dim'] += '---*-*';
chords['c_dim'] += '------';
chords['c_dim'] += '------';
chords['c_dim'] += '0'; // fret

chords['c_maj7']  = 'x--ooo';
chords['c_maj7'] += '------';
chords['c_maj7'] += '--*---';
chords['c_maj7'] += '-*----';
chords['c_maj7'] += '------';
chords['c_maj7'] += '0'; // fret

chords['c_9']  = 'x----x';
chords['c_9'] += '------';
chords['c_9'] += '--*---';
chords['c_9'] += '-*-**-';
chords['c_9'] += '------';
chords['c_9'] += '0'; // fret

chords['c_11']  = 'x-----';
chords['c_11'] += '------';
chords['c_11'] += '-<===>';
chords['c_11'] += '-*****';
chords['c_11'] += '------';
chords['c_11'] += '0'; // fret

chords['c_7aug']  = 'xx----';
chords['c_7aug'] += '---**-';
chords['c_7aug'] += '--*--*';
chords['c_7aug'] += '------';
chords['c_7aug'] += '------';
chords['c_7aug'] += '5'; // fret

chords['c_7sus4']  = 'x-----';
chords['c_7sus4'] += '----**';
chords['c_7sus4'] += '------';
chords['c_7sus4'] += '-***--';
chords['c_7sus4'] += '------';
chords['c_7sus4'] += '0'; // fret


// C#/Db
chords['c#_maj']  = 'xx----';
chords['c#_maj'] += '---*-*';
chords['c#_maj'] += '----*-';
chords['c#_maj'] += '--*---';
chords['c#_maj'] += '------';
chords['c#_maj'] += '0'; // fret

chords['c#_7']  = 'x----x';
chords['c#_7'] += '------';
chords['c#_7'] += '----*-';
chords['c#_7'] += '--*---';
chords['c#_7'] += '-*-*--';
chords['c#_7'] += '0'; // fret

chords['c#_6']  = 'x----x';
chords['c#_6'] += '------';
chords['c#_6'] += '----*-';
chords['c#_6'] += '--**--';
chords['c#_6'] += '-*----';
chords['c#_6'] += '0'; // fret

chords['c#_min']  = 'xx---o';
chords['c#_min'] += '---*--';
chords['c#_min'] += '--*-*-';
chords['c#_min'] += '------';
chords['c#_min'] += '------';
chords['c#_min'] += '0'; // fret

chords['c#_min7']  = 'o----o';
chords['c#_min7'] += '---*--';
chords['c#_min7'] += '-**-*-';
chords['c#_min7'] += '------';
chords['c#_min7'] += '------';
chords['c#_min7'] += '0'; // fret

chords['c#_min6']  = 'xx----';
chords['c#_min6'] += '------';
chords['c#_min6'] += '--*-*-';
chords['c#_min6'] += '---*--';
chords['c#_min6'] += '-----*';
chords['c#_min6'] += '0'; // fret

chords['c#_aug']  = 'x----x';
chords['c#_aug'] += '------';
chords['c#_aug'] += '---**-';
chords['c#_aug'] += '--*---';
chords['c#_aug'] += '-*----';
chords['c#_aug'] += '0'; // fret

chords['c#_dim']  = 'xx----';
chords['c#_dim'] += '------';
chords['c#_dim'] += '--*-*-';
chords['c#_dim'] += '---*-*';
chords['c#_dim'] += '------';
chords['c#_dim'] += '0'; // fret

chords['c#_maj7']  = 'x--<=>';
chords['c#_maj7'] += '---***';
chords['c#_maj7'] += '------';
chords['c#_maj7'] += '--*---';
chords['c#_maj7'] += '-*----';
chords['c#_maj7'] += '0'; // fret

chords['c#_9']  = 'x----x';
chords['c#_9'] += '------';
chords['c#_9'] += '------';
chords['c#_9'] += '--*---';
chords['c#_9'] += '-*-**-';
chords['c#_9'] += '0'; // fret

chords['c#_11']  = 'x-----';
chords['c#_11'] += '------';
chords['c#_11'] += '------';
chords['c#_11'] += '-<===>';
chords['c#_11'] += '-*****';
chords['c#_11'] += '0'; // fret

chords['c#_7aug']  = 'x---o-';
chords['c#_7aug'] += '-----*';
chords['c#_7aug'] += '---*--';
chords['c#_7aug'] += '--*---';
chords['c#_7aug'] += '-*----';
chords['c#_7aug'] += '0'; // fret

chords['c#_7sus4']  = 'x----x';
chords['c#_7sus4'] += '-*-*--';
chords['c#_7sus4'] += '------';
chords['c#_7sus4'] += '--*---';
chords['c#_7sus4'] += '----*-';
chords['c#_7sus4'] += '4'; // fret


// D
chords['d_maj']  = 'xoo---';
chords['d_maj'] += '------';
chords['d_maj'] += '---*-*';
chords['d_maj'] += '----*-';
chords['d_maj'] += '------';
chords['d_maj'] += '0'; // fret

chords['d_7']  = 'xxo---';
chords['d_7'] += '----*-';
chords['d_7'] += '---*-*';
chords['d_7'] += '------';
chords['d_7'] += '------';
chords['d_7'] += '0'; // fret

chords['d_6']  = 'xxo-o-';
chords['d_6'] += '------';
chords['d_6'] += '---*-*';
chords['d_6'] += '------';
chords['d_6'] += '------';
chords['d_6'] += '0'; // fret

chords['d_min']  = 'xoo---';
chords['d_min'] += '-----*';
chords['d_min'] += '---*--';
chords['d_min'] += '----*-';
chords['d_min'] += '------';
chords['d_min'] += '0'; // fret

chords['d_min7']  = 'xxo---';
chords['d_min7'] += '----**';
chords['d_min7'] += '---*--';
chords['d_min7'] += '------';
chords['d_min7'] += '------';
chords['d_min7'] += '0'; // fret

chords['d_min6']  = 'xxo-o-';
chords['d_min6'] += '-----*';
chords['d_min6'] += '---*--';
chords['d_min6'] += '------';
chords['d_min6'] += '------';
chords['d_min6'] += '0'; // fret

chords['d_aug']  = 'xxo---';
chords['d_aug'] += '------';
chords['d_aug'] += '-----*';
chords['d_aug'] += '---**-';
chords['d_aug'] += '------';
chords['d_aug'] += '0'; // fret

chords['d_dim']  = 'xxo-o-';
chords['d_dim'] += '---*-*';
chords['d_dim'] += '------';
chords['d_dim'] += '------';
chords['d_dim'] += '------';
chords['d_dim'] += '0'; // fret

chords['d_maj7']  = 'xxo---';
chords['d_maj7'] += '---<=>';
chords['d_maj7'] += '---***';
chords['d_maj7'] += '------';
chords['d_maj7'] += '------';
chords['d_maj7'] += '0'; // fret

chords['d_9']  = 'x----x';
chords['d_9'] += '--*---';
chords['d_9'] += '-*-**-';
chords['d_9'] += '------';
chords['d_9'] += '------';
chords['d_9'] += '4'; // fret

chords['d_11']  = 'x<===>';
chords['d_11'] += '-*****';
chords['d_11'] += '------';
chords['d_11'] += '------';
chords['d_11'] += '------';
chords['d_11'] += '5'; // fret

chords['d_7aug']  = 'xxo---';
chords['d_7aug'] += '----*-';
chords['d_7aug'] += '-----*';
chords['d_7aug'] += '---*--';
chords['d_7aug'] += '------';
chords['d_7aug'] += '0'; // fret

chords['d_7sus4']  = 'xooo--';
chords['d_7sus4'] += '----*-';
chords['d_7sus4'] += '------';
chords['d_7sus4'] += '-----*';
chords['d_7sus4'] += '------';
chords['d_7sus4'] += '0'; // fret


// D#/Eb
chords['d#_maj']  = 'xx-<=>';
chords['d#_maj'] += '---*-*';
chords['d#_maj'] += '----*-';
chords['d#_maj'] += '--*---';
chords['d#_maj'] += '------';
chords['d#_maj'] += '3'; // fret

chords['d#_7']  = 'xx----';
chords['d#_7'] += '--*---';
chords['d#_7'] += '----*-';
chords['d#_7'] += '---*-*';
chords['d#_7'] += '------';
chords['d#_7'] += '0'; // fret

chords['d#_6']  = 'x----x';
chords['d#_6'] += '----*-';
chords['d#_6'] += '--**--';
chords['d#_6'] += '-*----';
chords['d#_6'] += '------';
chords['d#_6'] += '4'; // fret

chords['d#_min']  = 'xx----';
chords['d#_min'] += '------';
chords['d#_min'] += '-----*';
chords['d#_min'] += '---*--';
chords['d#_min'] += '--*-*-';
chords['d#_min'] += '0'; // fret

chords['d#_min7']  = 'xx----';
chords['d#_min7'] += '--*---';
chords['d#_min7'] += '----**';
chords['d#_min7'] += '---*--';
chords['d#_min7'] += '------';
chords['d#_min7'] += '0'; // fret

chords['d#_min6']  = 'xx----';
chords['d#_min6'] += '--*-*-';
chords['d#_min6'] += '-----*';
chords['d#_min6'] += '---*--';
chords['d#_min6'] += '------';
chords['d#_min6'] += '0'; // fret

chords['d#_aug']  = 'xx-oo-';
chords['d#_aug'] += '--*---';
chords['d#_aug'] += '------';
chords['d#_aug'] += '-----*';
chords['d#_aug'] += '------';
chords['d#_aug'] += '0'; // fret

chords['d#_dim']  = 'xx----';
chords['d#_dim'] += '--*-*-';
chords['d#_dim'] += '---*-*';
chords['d#_dim'] += '------';
chords['d#_dim'] += '------';
chords['d#_dim'] += '0'; // fret

chords['d#_maj7']  = 'xx----';
chords['d#_maj7'] += '--*---';
chords['d#_maj7'] += '------';
chords['d#_maj7'] += '---***';
chords['d#_maj7'] += '------';
chords['d#_maj7'] += '0'; // fret

chords['d#_9']  = 'x--o-x';
chords['d#_9'] += '-**--*';
chords['d#_9'] += '----*-';
chords['d#_9'] += '------';
chords['d#_9'] += '------';
chords['d#_9'] += '0'; // fret

chords['d#_11']  = 'x<===>';
chords['d#_11'] += '-*****';
chords['d#_11'] += '------';
chords['d#_11'] += '------';
chords['d#_11'] += '------';
chords['d#_11'] += '6'; // fret

chords['d#_7aug']  = 'xx----';
chords['d#_7aug'] += '--*---';
chords['d#_7aug'] += '----*-';
chords['d#_7aug'] += '-----*';
chords['d#_7aug'] += '---*--';
chords['d#_7aug'] += '0'; // fret

chords['d#_7sus4']  = 'xx----';
chords['d#_7sus4'] += '--*---';
chords['d#_7sus4'] += '----*-';
chords['d#_7sus4'] += '---*--';
chords['d#_7sus4'] += '-----*';
chords['d#_7sus4'] += '0'; // fret


// E
chords['e_maj']  = 'o---oo';
chords['e_maj'] += '---*--';
chords['e_maj'] += '-**---';
chords['e_maj'] += '------';
chords['e_maj'] += '------';
chords['e_maj'] += '0'; // fret

chords['e_7']  = 'o-o-oo';
chords['e_7'] += '---*--';
chords['e_7'] += '-*----';
chords['e_7'] += '------';
chords['e_7'] += '------';
chords['e_7'] += '0'; // fret

chords['e_6']  = 'x----o';
chords['e_6'] += '---*--';
chords['e_6'] += '-**-*-';
chords['e_6'] += '------';
chords['e_6'] += '------';
chords['e_6'] += '0'; // fret

chords['e_min']  = 'o--ooo';
chords['e_min'] += '------';
chords['e_min'] += '-**---';
chords['e_min'] += '------';
chords['e_min'] += '------';
chords['e_min'] += '0'; // fret

chords['e_min7']  = 'o--o-o';
chords['e_min7'] += '------';
chords['e_min7'] += '-**---';
chords['e_min7'] += '----*-';
chords['e_min7'] += '------';
chords['e_min7'] += '0'; // fret

chords['e_min6']  = 'o--o-o';
chords['e_min6'] += '------';
chords['e_min6'] += '-**-*-';
chords['e_min6'] += '------';
chords['e_min6'] += '------';
chords['e_min6'] += '0'; // fret

chords['e_aug']  = 'xx---o';
chords['e_aug'] += '---**-';
chords['e_aug'] += '--*---';
chords['e_aug'] += '------';
chords['e_aug'] += '------';
chords['e_aug'] += '0'; // fret

chords['e_dim']  = 'x--x-x';
chords['e_dim'] += '-*----';
chords['e_dim'] += '--*-*-';
chords['e_dim'] += '------';
chords['e_dim'] += '------';
chords['e_dim'] += '0'; // fret

chords['e_maj7']  = 'xx----';
chords['e_maj7'] += '------';
chords['e_maj7'] += '--*---';
chords['e_maj7'] += '------';
chords['e_maj7'] += '---***';
chords['e_maj7'] += '0'; // fret

chords['e_9']  = 'o-o-o-';
chords['e_9'] += '---*--';
chords['e_9'] += '-*---*';
chords['e_9'] += '------';
chords['e_9'] += '------';
chords['e_9'] += '0'; // fret

chords['e_11']  = 'x-----';
chords['e_11'] += '-<===>';
chords['e_11'] += '-***-*';
chords['e_11'] += '----*-';
chords['e_11'] += '------';
chords['e_11'] += '0'; // fret

chords['e_7aug']  = 'xxo--o';
chords['e_7aug'] += '---**-';
chords['e_7aug'] += '------';
chords['e_7aug'] += '------';
chords['e_7aug'] += '------';
chords['e_7aug'] += '0'; // fret

chords['e_7sus4']  = 'o-o-oo';
chords['e_7sus4'] += '------';
chords['e_7sus4'] += '-*-*--';
chords['e_7sus4'] += '------';
chords['e_7sus4'] += '------';
chords['e_7sus4'] += '0'; // fret


// F
chords['f_maj']  = 'xx----';
chords['f_maj'] += '----**';
chords['f_maj'] += '---*--';
chords['f_maj'] += '--*---';
chords['f_maj'] += '------';
chords['f_maj'] += '0'; // fret

chords['f_7']  = 'xx----';
chords['f_7'] += '--*---';
chords['f_7'] += '----*-';
chords['f_7'] += '---*-*';
chords['f_7'] += '------';
chords['f_7'] += '3'; // fret

chords['f_6']  = 'xo----';
chords['f_6'] += '-----*';
chords['f_6'] += '---*--';
chords['f_6'] += '--*-*-';
chords['f_6'] += '------';
chords['f_6'] += '0'; // fret

chords['f_min']  = 'xx-<=>';
chords['f_min'] += '---***';
chords['f_min'] += '------';
chords['f_min'] += '--*---';
chords['f_min'] += '------';
chords['f_min'] += '0'; // fret

chords['f_min7']  = 'xx<==>';
chords['f_min7'] += '--****';
chords['f_min7'] += '------';
chords['f_min7'] += '------';
chords['f_min7'] += '------';
chords['f_min7'] += '0'; // fret

chords['f_min6']  = 'xxo---';
chords['f_min6'] += '---***';
chords['f_min6'] += '------';
chords['f_min6'] += '------';
chords['f_min6'] += '------';
chords['f_min6'] += '0'; // fret

chords['f_aug']  = 'xx----';
chords['f_aug'] += '-----*';
chords['f_aug'] += '---**-';
chords['f_aug'] += '--*---';
chords['f_aug'] += '------';
chords['f_aug'] += '0'; // fret

chords['f_dim']  = 'xxo-o-';
chords['f_dim'] += '---*-*';
chords['f_dim'] += '------';
chords['f_dim'] += '------';
chords['f_dim'] += '------';
chords['f_dim'] += '0'; // fret

chords['f_maj7']  = 'xx---o';
chords['f_maj7'] += '----*-';
chords['f_maj7'] += '---*--';
chords['f_maj7'] += '--*---';
chords['f_maj7'] += '------';
chords['f_maj7'] += '0'; // fret

chords['f_9']  = 'xx----';
chords['f_9'] += '------';
chords['f_9'] += '---*--';
chords['f_9'] += '--*--*';
chords['f_9'] += '----*-';
chords['f_9'] += '0'; // fret

chords['f_11']  = 'x<===>';
chords['f_11'] += '-***-*';
chords['f_11'] += '----*-';
chords['f_11'] += '------';
chords['f_11'] += '------';
chords['f_11'] += '3'; // fret

chords['f_7aug']  = '-o---x';
chords['f_7aug'] += '*-*---';
chords['f_7aug'] += '---**-';
chords['f_7aug'] += '------';
chords['f_7aug'] += '------';
chords['f_7aug'] += '0'; // fret

chords['f_7sus4']  = '----xx';
chords['f_7sus4'] += '*-*---';
chords['f_7sus4'] += '------';
chords['f_7sus4'] += '-*-*--';
chords['f_7sus4'] += '------';
chords['f_7sus4'] += '0'; // fret


// F#/Gb
chords['f#_maj']  = 'xx----';
chords['f#_maj'] += '------';
chords['f#_maj'] += '----**';
chords['f#_maj'] += '---*--';
chords['f#_maj'] += '--*---';
chords['f#_maj'] += '0'; // fret

chords['f#_7']  = 'x----o';
chords['f#_7'] += '------';
chords['f#_7'] += '----*-';
chords['f#_7'] += '---*--';
chords['f#_7'] += '-**---';
chords['f#_7'] += '0'; // fret

chords['f#_6']  = 'xx----';
chords['f#_6'] += '------';
chords['f#_6'] += '-----*';
chords['f#_6'] += '---*--';
chords['f#_6'] += '--*-*-';
chords['f#_6'] += '0'; // fret

chords['f#_min']  = 'xx----';
chords['f#_min'] += '---<=>';
chords['f#_min'] += '---***';
chords['f#_min'] += '------';
chords['f#_min'] += '--*---';
chords['f#_min'] += '0'; // fret

chords['f#_min7']  = 'xx----';
chords['f#_min7'] += '--<==>';
chords['f#_min7'] += '--****';
chords['f#_min7'] += '------';
chords['f#_min7'] += '------';
chords['f#_min7'] += '0'; // fret

chords['f#_min6']  = 'xx----';
chords['f#_min6'] += '--*---';
chords['f#_min6'] += '---***';
chords['f#_min6'] += '------';
chords['f#_min6'] += '------';
chords['f#_min6'] += '0'; // fret

chords['f#_aug']  = 'xx----';
chords['f#_aug'] += '------';
chords['f#_aug'] += '-----*';
chords['f#_aug'] += '---**-';
chords['f#_aug'] += '--*---';
chords['f#_aug'] += '0'; // fret

chords['f#_dim']  = 'xx----';
chords['f#_dim'] += '--*-*-';
chords['f#_dim'] += '---*-*';
chords['f#_dim'] += '------';
chords['f#_dim'] += '------';
chords['f#_dim'] += '0'; // fret

chords['f#_maj7']  = 'xx----';
chords['f#_maj7'] += '-----*';
chords['f#_maj7'] += '----*-';
chords['f#_maj7'] += '---*--';
chords['f#_maj7'] += '--*---';
chords['f#_maj7'] += '0'; // fret

chords['f#_9']  = '------';
chords['f#_9'] += '---*--';
chords['f#_9'] += '--*--*';
chords['f#_9'] += '----*-';
chords['f#_9'] += '------';
chords['f#_9'] += '3'; // fret

chords['f#_11']  = '------';
chords['f#_11'] += '<===>-';
chords['f#_11'] += '***-*-';
chords['f#_11'] += '---*--';
chords['f#_11'] += '-----*';
chords['f#_11'] += '0'; // fret

chords['f#_7aug']  = '--o--o';
chords['f#_7aug'] += '-*----';
chords['f#_7aug'] += '*-----';
chords['f#_7aug'] += '---**-';
chords['f#_7aug'] += '------';
chords['f#_7aug'] += '0'; // fret

chords['f#_7sus4']  = '----xx';
chords['f#_7sus4'] += '------';
chords['f#_7sus4'] += '*-*---';
chords['f#_7sus4'] += '------';
chords['f#_7sus4'] += '-*-*--';
chords['f#_7sus4'] += '0'; // fret


// G
chords['g_maj']  = '--ooo-';
chords['g_maj'] += '------';
chords['g_maj'] += '-*----';
chords['g_maj'] += '*----*';
chords['g_maj'] += '------';
chords['g_maj'] += '0'; // fret

chords['g_7']  = '--ooo-';
chords['g_7'] += '-----*';
chords['g_7'] += '-*----';
chords['g_7'] += '*-----';
chords['g_7'] += '------';
chords['g_7'] += '0'; // fret

chords['g_6']  = '--oooo';
chords['g_6'] += '------';
chords['g_6'] += '-*----';
chords['g_6'] += '*-----';
chords['g_6'] += '------';
chords['g_6'] += '0'; // fret

chords['g_min']  = 'xxo---';
chords['g_min'] += '------';
chords['g_min'] += '------';
chords['g_min'] += '---***';
chords['g_min'] += '------';
chords['g_min'] += '0'; // fret

chords['g_min7']  = 'xx----';
chords['g_min7'] += '------';
chords['g_min7'] += '--<==>';
chords['g_min7'] += '--****';
chords['g_min7'] += '------';
chords['g_min7'] += '0'; // fret

chords['g_min6']  = 'xx----';
chords['g_min6'] += '------';
chords['g_min6'] += '--*---';
chords['g_min6'] += '---***';
chords['g_min6'] += '------';
chords['g_min6'] += '0'; // fret

chords['g_aug']  = 'xx-oo-';
chords['g_aug'] += '--*---';
chords['g_aug'] += '------';
chords['g_aug'] += '-----*';
chords['g_aug'] += '------';
chords['g_aug'] += '0'; // fret

chords['g_dim']  = 'xx----';
chords['g_dim'] += '------';
chords['g_dim'] += '--*-*-';
chords['g_dim'] += '---*-*';
chords['g_dim'] += '------';
chords['g_dim'] += '0'; // fret

chords['g_maj7']  = '--ooo-';
chords['g_maj7'] += '------';
chords['g_maj7'] += '-*---*';
chords['g_maj7'] += '*-----';
chords['g_maj7'] += '------';
chords['g_maj7'] += '0'; // fret

chords['g_9']  = '--o-o-';
chords['g_9'] += '-----*';
chords['g_9'] += '-*-*--';
chords['g_9'] += '*-----';
chords['g_9'] += '------';
chords['g_9'] += '0'; // fret

chords['g_11']  = '--o-o-';
chords['g_11'] += '-----*';
chords['g_11'] += '---*--';
chords['g_11'] += '**----';
chords['g_11'] += '------';
chords['g_11'] += '0'; // fret

chords['g_7aug']  = 'xx----';
chords['g_7aug'] += '------';
chords['g_7aug'] += '------';
chords['g_7aug'] += '--*--*';
chords['g_7aug'] += '---**-';
chords['g_7aug'] += '0'; // fret

chords['g_7sus4']  = '----xx';
chords['g_7sus4'] += '*-*---';
chords['g_7sus4'] += '------';
chords['g_7sus4'] += '-*-*--';
chords['g_7sus4'] += '------';
chords['g_7sus4'] += '0'; // fret


// G#/Ab
chords['g#_maj']  = 'xx----';
chords['g#_maj'] += '----**';
chords['g#_maj'] += '---*--';
chords['g#_maj'] += '--*---';
chords['g#_maj'] += '------';
chords['g#_maj'] += '4'; // fret

chords['g#_7']  = 'xx----';
chords['g#_7'] += '--***-';
chords['g#_7'] += '-----*';
chords['g#_7'] += '------';
chords['g#_7'] += '------';
chords['g#_7'] += '0'; // fret

chords['g#_6']  = 'xx----';
chords['g#_6'] += '-----*';
chords['g#_6'] += '---*--';
chords['g#_6'] += '--*-*-';
chords['g#_6'] += '------';
chords['g#_6'] += '4'; // fret

chords['g#_min']  = 'xx-<=>';
chords['g#_min'] += '---***';
chords['g#_min'] += '------';
chords['g#_min'] += '--*---';
chords['g#_min'] += '------';
chords['g#_min'] += '4'; // fret

chords['g#_min7']  = 'xx----';
chords['g#_min7'] += '------';
chords['g#_min7'] += '------';
chords['g#_min7'] += '--<==>';
chords['g#_min7'] += '--****';
chords['g#_min7'] += '0'; // fret

chords['g#_min6']  = 'xx----';
chords['g#_min6'] += '------';
chords['g#_min6'] += '------';
chords['g#_min6'] += '--*---';
chords['g#_min6'] += '---***';
chords['g#_min6'] += '0'; // fret

chords['g#_aug']  = 'xx---o-';
chords['g#_aug'] += '---**-';
chords['g#_aug'] += '--*---';
chords['g#_aug'] += '------';
chords['g#_aug'] += '------';
chords['g#_aug'] += '0'; // fret

chords['g#_dim']  = 'xxo-o-';
chords['g#_dim'] += '---*-*';
chords['g#_dim'] += '------';
chords['g#_dim'] += '------';
chords['g#_dim'] += '------';
chords['g#_dim'] += '0'; // fret

chords['g#_maj7']  = 'xx----';
chords['g#_maj7'] += '----**';
chords['g#_maj7'] += '--**--';
chords['g#_maj7'] += '------';
chords['g#_maj7'] += '------';
chords['g#_maj7'] += '4'; // fret

chords['g#_9']  = '----xx';
chords['g#_9'] += '------';
chords['g#_9'] += '------';
chords['g#_9'] += '-*-*--';
chords['g#_9'] += '*-*---';
chords['g#_9'] += '0'; // fret

chords['g#_11']  = '-<===>';
chords['g#_11'] += '-*****';
chords['g#_11'] += '------';
chords['g#_11'] += '------';
chords['g#_11'] += '------';
chords['g#_11'] += '1'; // fret

chords['g#_7aug']  = 'xx----';
chords['g#_7aug'] += '---**-';
chords['g#_7aug'] += '--*--*';
chords['g#_7aug'] += '------';
chords['g#_7aug'] += '------';
chords['g#_7aug'] += '0'; // fret

chords['g#_7sus4']  = 'xx----';
chords['g#_7sus4'] += '--**--';
chords['g#_7sus4'] += '----**';
chords['g#_7sus4'] += '------';
chords['g#_7sus4'] += '------';
chords['g#_7sus4'] += '3'; // fret


// A
chords['a_maj']  = 'xo---o';
chords['a_maj'] += '------';
chords['a_maj'] += '--***-';
chords['a_maj'] += '------';
chords['a_maj'] += '------';
chords['a_maj'] += '0'; // fret

chords['a_7']  = 'xo----';
chords['a_7'] += '--<=>-';
chords['a_7'] += '--***-';
chords['a_7'] += '-----*';
chords['a_7'] += '------';
chords['a_7'] += '0'; // fret

chords['a_6']  = 'xo----';
chords['a_6'] += '--<==>';
chords['a_6'] += '--****';
chords['a_6'] += '------';
chords['a_6'] += '------';
chords['a_6'] += '0'; // fret

chords['a_min']  = 'xo---o';
chords['a_min'] += '----*-';
chords['a_min'] += '--**--';
chords['a_min'] += '------';
chords['a_min'] += '------';
chords['a_min'] += '0'; // fret

chords['a_min7']  = 'xo-o-o';
chords['a_min7'] += '----*-';
chords['a_min7'] += '--*---';
chords['a_min7'] += '------';
chords['a_min7'] += '------';
chords['a_min7'] += '0'; // fret

chords['a_min6']  = 'xo----';
chords['a_min6'] += '----*-';
chords['a_min6'] += '--**-*';
chords['a_min6'] += '------';
chords['a_min6'] += '------';
chords['a_min6'] += '0'; // fret

chords['a_aug']  = 'xo----';
chords['a_aug'] += '-----*';
chords['a_aug'] += '---**-';
chords['a_aug'] += '--*---';
chords['a_aug'] += '------';
chords['a_aug'] += '0'; // fret

chords['a_dim']  = 'xo----';
chords['a_dim'] += '--*-*-';
chords['a_dim'] += '---*-*';
chords['a_dim'] += '------';
chords['a_dim'] += '------';
chords['a_dim'] += '0'; // fret

chords['a_maj7']  = 'xo---o';
chords['a_maj7'] += '---*--';
chords['a_maj7'] += '--*-*-';
chords['a_maj7'] += '------';
chords['a_maj7'] += '------';
chords['a_maj7'] += '0'; // fret

chords['a_9']  = 'xo----';
chords['a_9'] += '--<=>-';
chords['a_9'] += '--*-*-';
chords['a_9'] += '-----*';
chords['a_9'] += '---*--';
chords['a_9'] += '0'; // fret

chords['a_11']  = 'xooooo';
chords['a_11'] += '------';
chords['a_11'] += '------';
chords['a_11'] += '------';
chords['a_11'] += '------';
chords['a_11'] += '0'; // fret

chords['a_7aug']  = 'xo----';
chords['a_7aug'] += '------';
chords['a_7aug'] += '---**-';
chords['a_7aug'] += '--*--*';
chords['a_7aug'] += '------';
chords['a_7aug'] += '0'; // fret

chords['a_7sus4']  = 'xo-o-o';
chords['a_7sus4'] += '------';
chords['a_7sus4'] += '--*---';
chords['a_7sus4'] += '----*-';
chords['a_7sus4'] += '------';
chords['a_7sus4'] += '0'; // fret


// A#/Bb
chords['a#_maj']  = 'xx----';
chords['a#_maj'] += '-----*';
chords['a#_maj'] += '------';
chords['a#_maj'] += '--***-';
chords['a#_maj'] += '------';
chords['a#_maj'] += '0'; // fret

chords['a#_7']  = 'x----x';
chords['a#_7'] += '-*-*--';
chords['a#_7'] += '------';
chords['a#_7'] += '--*-*-';
chords['a#_7'] += '------';
chords['a#_7'] += '0'; // fret

chords['a#_6']  = 'xx----';
chords['a#_6'] += '------';
chords['a#_6'] += '--<==>';
chords['a#_6'] += '--****';
chords['a#_6'] += '------';
chords['a#_6'] += '0'; // fret

chords['a#_min']  = 'xx----';
chords['a#_min'] += '-----*';
chords['a#_min'] += '----*-';
chords['a#_min'] += '--**--';
chords['a#_min'] += '------';
chords['a#_min'] += '0'; // fret

chords['a#_min7']  = 'xx----';
chords['a#_min7'] += '------';
chords['a#_min7'] += '----*-';
chords['a#_min7'] += '--**--';
chords['a#_min7'] += '-----*';
chords['a#_min7'] += '0'; // fret

chords['a#_min6']  = 'xx----';
chords['a#_min6'] += '------';
chords['a#_min6'] += '----*-';
chords['a#_min6'] += '--**-*';
chords['a#_min6'] += '------';
chords['a#_min6'] += '0'; // fret

chords['a#_aug']  = 'xxo---';
chords['a#_aug'] += '------';
chords['a#_aug'] += '-----*';
chords['a#_aug'] += '---**-';
chords['a#_aug'] += '------';
chords['a#_aug'] += '0'; // fret

chords['a#_dim']  = 'x--o-x';
chords['a#_dim'] += '-*----';
chords['a#_dim'] += '--*-*-';
chords['a#_dim'] += '------';
chords['a#_dim'] += '------';
chords['a#_dim'] += '0'; // fret

chords['a#_maj7']  = 'x----x';
chords['a#_maj7'] += '-*----';
chords['a#_maj7'] += '---*--';
chords['a#_maj7'] += '--*-*-';
chords['a#_maj7'] += '------';
chords['a#_maj7'] += '0'; // fret

chords['a#_9']  = 'x-o---';
chords['a#_9'] += '-*-***';
chords['a#_9'] += '------';
chords['a#_9'] += '------';
chords['a#_9'] += '------';
chords['a#_9'] += '0'; // fret

chords['a#_11']  = 'x<===>';
chords['a#_11'] += '-*****';
chords['a#_11'] += '------';
chords['a#_11'] += '------';
chords['a#_11'] += '------';
chords['a#_11'] += '0'; // fret

chords['a#_7aug']  = 'xx----';
chords['a#_7aug'] += '--*--*';
chords['a#_7aug'] += '---**-';
chords['a#_7aug'] += '------';
chords['a#_7aug'] += '------';
chords['a#_7aug'] += '6'; // fret

chords['a#_7sus4']  = 'x----x';
chords['a#_7sus4'] += '-*-*--';
chords['a#_7sus4'] += '------';
chords['a#_7sus4'] += '--*---';
chords['a#_7sus4'] += '----*-';
chords['a#_7sus4'] += '0'; // fret


// B
chords['b_maj']  = 'xx----';
chords['b_maj'] += '------';
chords['b_maj'] += '-----*';
chords['b_maj'] += '------';
chords['b_maj'] += '--***-';
chords['b_maj'] += '0'; // fret

chords['b_7']  = 'x---o-';
chords['b_7'] += '--*---';
chords['b_7'] += '-*-*-*';
chords['b_7'] += '------';
chords['b_7'] += '------';
chords['b_7'] += '0'; // fret

chords['b_6']  = 'xx----';
chords['b_6'] += '------';
chords['b_6'] += '------';
chords['b_6'] += '--<==>';
chords['b_6'] += '--****';
chords['b_6'] += '0'; // fret

chords['b_min']  = 'xx----';
chords['b_min'] += '------';
chords['b_min'] += '-----*';
chords['b_min'] += '----*-';
chords['b_min'] += '--**--';
chords['b_min'] += '0'; // fret

chords['b_min7']  = 'xoo---';
chords['b_min7'] += '------';
chords['b_min7'] += '-----*';
chords['b_min7'] += '----*-';
chords['b_min7'] += '---*--';
chords['b_min7'] += '0'; // fret

chords['b_min6']  = 'xx----';
chords['b_min6'] += '------';
chords['b_min6'] += '------';
chords['b_min6'] += '----*-';
chords['b_min6'] += '--**-*';
chords['b_min6'] += '0'; // fret

chords['b_aug']  = 'x--oox';
chords['b_aug'] += '--*---';
chords['b_aug'] += '-*----';
chords['b_aug'] += '------';
chords['b_aug'] += '------';
chords['b_aug'] += '0'; // fret

chords['b_dim']  = 'x-o-o-';
chords['b_dim'] += '---*-*';
chords['b_dim'] += '------';
chords['b_dim'] += '------';
chords['b_dim'] += '------';
chords['b_dim'] += '0'; // fret

chords['b_maj7']  = 'x----x';
chords['b_maj7'] += '------';
chords['b_maj7'] += '-*----';
chords['b_maj7'] += '---*--';
chords['b_maj7'] += '--*-*-';
chords['b_maj7'] += '0'; // fret

chords['b_9']  = 'x-----';
chords['b_9'] += '--*---';
chords['b_9'] += '-*-***';
chords['b_9'] += '------';
chords['b_9'] += '------';
chords['b_9'] += '0'; // fret

chords['b_11']  = 'x-----';
chords['b_11'] += '-<===>';
chords['b_11'] += '-*****';
chords['b_11'] += '------';
chords['b_11'] += '------';
chords['b_11'] += '0'; // fret

chords['b_7aug']  = 'x---o-';
chords['b_7aug'] += '--*---';
chords['b_7aug'] += '-*-*--';
chords['b_7aug'] += '-----*';
chords['b_7aug'] += '------';
chords['b_7aug'] += '0'; // fret

chords['b_7sus4']  = 'x----o';
chords['b_7sus4'] += '------';
chords['b_7sus4'] += '-*-*--';
chords['b_7sus4'] += '------';
chords['b_7sus4'] += '--*---';
chords['b_7sus4'] += '0'; // fret

