<?php
/**
 * monospace SEO settings for monospace.com's own pages (no WordPress; /hens is WordPress and has its own, in its mu-plugins).
 * Engine: monospace-seo/ (master: knowledge base tools/monospace-seo/). index.php prints its tags at run time;
 * php monospace-seo/build.php (in the docroot, on the server) writes sitemap.xml.
 */
return [
	'site'  => [
		'url'         => 'https://monospace.com',
		'site_name'   => 'monospace',
		'description' => 'Music, art, and interactive multimedia by Hens Breet since 1998.',
		'locale'      => 'en_US',
		'person'      => [
			'name'   => 'Hens Breet',
			'url'    => 'https://monospace.com/hens/',
			'sameAs' => [ 'https://art.monospace.com/', 'https://music.monospace.com/', 'https://www.instagram.com/hens.breet/', 'https://bsky.app/profile/monospace.com' ],
		],
		'image'       => [
			'url'    => 'https://monospace.com/assets/images/site_image.png',
			'width'  => 2900,
			'height' => 1834,
			'type'   => 'image/png',
			'alt'    => 'monospace',
		],
	],
	'pages' => [
		'/' => [
			'file'           => 'index.php',
			'title'          => 'monospace',
			'description'    => 'Music, art, and interactive multimedia by Hens Breet since 1998.',
			'og_description' => 'Music, Art, and Interactive Multimedia since 1998. You are visitor number one.',
		],
	],
];
