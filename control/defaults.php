<?php
/**
 * What the home page shows when nothing has been published: the panels as they were hand-written in index.php (until
 * 2026-10-28) and the panels' look. Read by index.php and by the control screen (the starting point for "back to the
 * starting values"). Returns an array; opening this file in a browser shows nothing.
 *
 * Panel: id (letters, digits, - _), title, text (plain; a blank line = a paragraph break), url ('' = not clickable),
 * image (a spotlight image under assets/images/spotlight/, '' = none), clear (no glass box, text only - the Welcome panel),
 * show, label (what screen readers say; empty = the title), look (optional: this panel's own look - any of width, max, radius,
 * tint, color, title_size, text_size - replaces the common one for this panel only).
 */

$S = 'assets/images/spotlight/';

return [
    'panels' => [
        ['id' => 'welcome', 'title' => '', 'clear' => true, 'show' => true, 'url' => '', 'image' => $S . 'hand.png', 'label' => 'Welcome section',
         'text' => "Welcome!\n\nScroll, swipe, or use arrow keys to navigate this page. Each section shows a different aspect of my work. Click or press Enter on a panel to explore further."],
        ['id' => 'art', 'title' => 'monospace ART', 'show' => true, 'url' => 'https://art.monospace.com', 'image' => $S . 'easel.png', 'label' => 'monospace ART - Painting and sketches',
         'text' => 'Painting and sketches exploring mood, light, and structure through landscapes, cityscapes, and nature studies. All works are available for purchase. Commissions welcome.'],
        ['id' => 'sightings', 'title' => 'Sightings of the Strangely Familiar', 'show' => true, 'url' => 'https://sightings.monospace.com', 'image' => $S . 'sightings.png', 'label' => 'Sightings of the Strangely Familiar - Digital sketches',
         'text' => 'A series of original digital sketches made with Procreate, captioned by Mindy.'],
        ['id' => 'food', 'title' => 'monospace FOOD', 'show' => true, 'url' => 'https://food.monospace.com', 'image' => $S . 'tocque.png', 'label' => 'Food photography',
         'text' => "A visual exploration of meals I've cooked, enjoyed, and photographed. A documentation of flavors, presentations, and culinary moments, shared to inspire and celebrate the experience of food."],
        ['id' => 'interactive', 'title' => 'monospace INTERACTIVE', 'show' => true, 'url' => 'https://monospace.com/hens/publishers-clearing-house/monthly-facebook-mini-games/', 'image' => $S . 'controller.png', 'label' => 'monospace INTERACTIVE - Web-based games and multimedia',
         'text' => 'I have decades of hands-on experience designing and coding interactive experiences that are both engaging and memorable, from web-based mini-games to immersive multimedia projects.'],
        ['id' => 'console', 'title' => 'THE MONOSPACE CONSOLE', 'show' => true, 'url' => 'console', 'image' => $S . 'console.png', 'label' => 'THE MONOSPACE CONSOLE - 1998 Shockwave project',
         'text' => 'The original monospace console, which started this website. A Shockwave art project from 1998, revived in modern Javascript. A chatty command-line console you can click, type, and poke at.'],
        ['id' => 'wayback', 'title' => '2001 TIME CAPSULE', 'show' => true, 'url' => 'wayback', 'image' => $S . 'cabinet.png', 'label' => '2001 TIME CAPSULE - Historical website snapshot',
         'text' => "This website has been around since 1998. It's changed many times since then, but it was always state of the art. Here is a snapshot of what it looked like in July of 2001, as found on the Wayback Machine."],
        ['id' => 'digits', 'title' => 'DIGITAL SEX LIFE', 'show' => true, 'url' => 'https://www.digitalsexlife.com', 'image' => $S . 'digits.png', 'label' => 'DIGITAL SEX LIFE - 1992 interactive artwork',
         'text' => 'An early interactive artwork from 1991-1992 where numbers become digital agents, moving, bonding, aging, and reproducing. Newly reimagined and rewritten.'],
        ['id' => 'color', 'title' => 'monospace COLOR', 'show' => true, 'url' => 'https://color.monospace.com', 'image' => $S . 'color.png', 'label' => 'monospace COLOR - Restored vintage photographs',
         'text' => 'I like to restore and colorize vintage black-and-white photographs by hand in Photoshop, paying close attention to historical accuracy and visual detail. Your "AI" can\'t do that.'],
        ['id' => 'music', 'title' => 'monospace MUSIC', 'show' => true, 'url' => 'https://music.monospace.com', 'image' => $S . 'drum.png', 'label' => 'monospace MUSIC - Original recordings',
         'text' => 'I play drums and guitar, and I write original music. Here you can find some of my recordings, live videos, and songs. (SOON)'],
        ['id' => 'portfolio', 'title' => 'PORTFOLIO', 'show' => true, 'url' => 'hens', 'image' => $S . 'briefcase.png', 'label' => 'PORTFOLIO - Professional work and resume',
         'text' => 'A curated selection of my professional work. Browse my resume, portfolio, and key projects across art, interactive media, and more.'],
    ],
    // The page around the panels: the contact link top right and the line at the bottom
    'site' => [
        'contact_show' => true,
        'contact_text' => 'contact',
        'contact_url'  => 'https://monospace.com/hens/contact/',
        'oval'         => false, // a white oval that stays put behind the spotlight image, so only the figure fades in and out
        'oval_grow'    => 2,     // px (of the 320 px wide image) larger than the oval drawn in the images
        'logo_layer'   => 'behind', // behind (under the light, as it always was) | light (above the light, under the panels) | front (above everything)
        'contact_style'  => 'plain',   // plain (the white link as it was) | glass | flat | outline - the art site's button looks
        'contact_color'  => 'blue',    // blue | black | grey | custom
        'contact_custom' => '#046bd2', // the colour when contact_color is custom
        'contact_radius' => 'pill',    // pill | 0-30 (px)
        'logo_src'     => '/assets/images/logo_xl_transp.png', // the logo picture: a path on this site or an https address
        'logo_pos'     => [ // per screen: % across (0 = left edge, 100 = right edge), vh down from the top, size in % of the screen width
            'desktop' => [10, 10, 55],
            'tablet'  => [10, 10, 55],
            'phone'   => [50, 5, 90],
            'landscape' => [6, 8, 42],
        ],
        'tagline_show'  => false, // the tagline under the logo: one switch for every screen
        'tagline_text'  => 'Music, Art, and Interactive Multimedia since 1998.',
        'tagline_font'  => 'courier', // courier (the page's own) | opensans | oswald | roboto | georgia
        'tagline_color' => '#ffffff',
        'tagline_style' => 'normal',  // normal | italic | bold | bolditalic | caps | glow
        'tagline_pos'   => [ // per screen: % across (0 = left edge, 50 = centred, 100 = right edge; it stays inside the screen), vh down from the top, size in px
            'desktop' => [10, 40, 22],
            'tablet'  => [10, 40, 20],
            'phone'   => [50, 36, 16],
            'landscape' => [6, 42, 14],
        ],
        'contact_size' => ['desktop' => 14, 'tablet' => 14, 'phone' => 14, 'landscape' => 13], // px, the button's text
        'footer'       => 'Copyright © 1998—2026 monospace.com. You are visitor number one.',
    ],
    // The panels' look: the values index.php's CSS starts with
    'look' => [
        'lefts' => ['desktop' => 10, 'tablet' => 10, 'phone' => 5, 'landscape' => 6], // per screen: the panels' left edge, in % of the screen's width (phone: the 90% wide panel sits centred = 5)
        'widths' => ['desktop' => [30, 400], 'tablet' => [30, 400], 'phone' => [90, 900], 'landscape' => [38, 480]], // per screen: panel width in % of the screen, up to this many px (never under 250)
        'radius' => 16,     // px
        'blur'  => 5,       // px of blur behind the glass
        'tint'  => ['#000000', 30], // the glass: colour, strength %
        'border' => 30,     // % - the thin white edge
        'shine' => 100,     // % - the light along the top and bottom edges
        'glow'  => 50,      // % - the glow when the pointer is over a panel (0 = none)
        'sizes' => ['desktop' => [24, 18], 'tablet' => [24, 18], 'phone' => [24, 18], 'landscape' => [18, 13]], // per screen: panel title size, text size (px)
        'color' => '#ffffff',
    ],
];
