<?php
/**
 * monospace SEO for sites without WordPress.
 *
 * The site's settings live in its docroot: monospace-seo.config.php (returns [ 'site' => [...], 'pages' => [ '/path' => [...] ] ]).
 * Engine and this file live in the docroot's monospace-seo/ folder (core.php, static.php, build.php); master copies in the
 * knowledge base, tools/monospace-seo/.
 *
 * Two ways in:
 *   - A PHP page prints its tags itself, in <head>:
 *       <?php require $_SERVER['DOCUMENT_ROOT'] . '/monospace-seo/static.php'; monospace_seo_static_head(); ?>
 *   - .html pages (the host's front server never runs PHP in them) get the same tags written in by build.php, between
 *     <!-- monospace-seo --> and <!-- /monospace-seo -->. build.php also writes sitemap.xml and the Sitemap line in robots.txt.
 *
 * Page settings: file (the page's file, from the docroot), title, description, og_title, og_description, type (og:type,
 * default website), image [url, width, height, type, alt] (default: the site's), graph (extra JSON-LD nodes), robots,
 * webpage (more fields for this page's WebPage node), noindex (true = robots noindex and left out of the sitemap), lastmod (Y-m-d; default: the file's date), canonical (false = none).
 * Site settings: url, site_name, description, locale, image, person, publisher, graph (JSON-LD nodes on every page).
 */

require_once __DIR__ . '/core.php';

/** The site's settings, from monospace-seo.config.php in the docroot. */
function monospace_seo_static_config( $root = null ) {
	static $cache = [];
	$root = rtrim( $root ?: dirname( __DIR__ ), '/' );
	if ( ! isset( $cache[ $root ] ) ) {
		$file = $root . '/monospace-seo.config.php';
		$cache[ $root ] = is_file( $file ) ? (array) require $file : [ 'site' => [], 'pages' => [] ];
	}
	return $cache[ $root ];
}

/** The render settings for one page address ('/', '/player.html'). Null when the page is not in the settings. */
function monospace_seo_static_page( array $config, $path ) {
	$site = $config['site'] ?? [];
	$page = $config['pages'][ $path ] ?? null;
	if ( $page === null ) return null;

	$home  = rtrim( $site['url'] ?? '', '/' );
	$url   = $home . $path;
	$image = $page['image'] ?? $site['image'] ?? null;
	$graph = array_merge( monospace_seo_base_graph( $site ), $site['graph'] ?? [] );
	$graph[] = array_merge( [
		'@type'       => 'WebPage',
		'@id'         => $url . '#webpage',
		'url'         => $url,
		'name'        => $page['title'] ?? '',
		'description' => $page['description'] ?? '',
		'isPartOf'    => [ '@id' => $home . '/#website' ],
		'primaryImageOfPage' => $image['url'] ?? null,
	], $page['webpage'] ?? [] );
	$graph = array_merge( $graph, $page['graph'] ?? [] );

	return [
		'title'          => $page['title'] ?? ( $site['site_name'] ?? '' ),
		'description'    => monospace_seo_trim( $page['description'] ?? ( $site['description'] ?? '' ) ),
		'og_title'       => $page['og_title'] ?? null,
		'og_description' => $page['og_description'] ?? null,
		'url'            => $url,
		'canonical'      => ( $page['canonical'] ?? true ) === false ? '' : $url,
		'site_name'      => $site['site_name'] ?? '',
		'locale'         => $page['locale'] ?? $site['locale'] ?? 'en_US',
		'type'           => $page['type'] ?? 'website',
		'image'          => $image,
		'robots'         => ! empty( $page['noindex'] ) ? 'noindex, follow' : ( $page['robots'] ?? 'max-image-preview:large' ),
		'graph'          => $graph,
		'extra'          => $page['extra'] ?? [],
		'print_title'    => true,
	];
}

/** For PHP pages: print this page's tags (title included). $path defaults to the address asked for. */
function monospace_seo_static_head( $path = null ) {
	if ( $path === null ) {
		$path = parse_url( $_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH ) ?: '/';
		$path = preg_replace( '#/index\.(php|html)$#', '/', $path );
	}
	$page = monospace_seo_static_page( monospace_seo_static_config(), $path );
	if ( $page ) echo "<!-- monospace SEO (monospace-seo/static.php) -->\n" . monospace_seo_render( $page )['html'];
}
