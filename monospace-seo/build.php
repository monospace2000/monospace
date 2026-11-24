<?php
/**
 * monospace SEO build for sites without WordPress (command line only).
 *
 *   php monospace-seo/build.php [--check]          (run from the docroot, on the server or on a local copy)
 *
 * Reads monospace-seo.config.php and:
 *   - writes each .html page's tags between <!-- monospace-seo --> and <!-- /monospace-seo --> (PHP pages print their own),
 *   - writes sitemap.xml (every page without noindex),
 *   - adds "Sitemap: <url>/sitemap.xml" to robots.txt when it is missing.
 * --check: say what would change, write nothing.
 */

if ( PHP_SAPI !== 'cli' ) exit;
require __DIR__ . '/static.php';

$root   = getcwd();
$check  = in_array( '--check', $argv, true );
$config = monospace_seo_static_config( $root );
$site   = $config['site'] ?? [];
if ( empty( $site['url'] ) ) { fwrite( STDERR, "No monospace-seo.config.php with a site url in $root\n" ); exit( 1 ); }
$home   = rtrim( $site['url'], '/' );
$urls   = [];
$errors = 0;

$write = function ( $file, $content, $what ) use ( $check ) {
	$old = is_file( $file ) ? file_get_contents( $file ) : null;
	if ( $old === $content ) { echo "  same      $what\n"; return; }
	echo ( $check ? '  would set ' : '  wrote     ' ) . "$what\n";
	if ( ! $check ) file_put_contents( $file, $content );
};

foreach ( $config['pages'] ?? [] as $path => $settings ) {
	$file = $root . '/' . ltrim( $settings['file'] ?? '', '/' );
	if ( empty( $settings['file'] ) || ! is_file( $file ) ) { echo "  MISSING   $path (" . ( $settings['file'] ?? 'no file' ) . ")\n"; $errors++; continue; }
	if ( empty( $settings['noindex'] ) ) $urls[] = [ 'loc' => $home . $path, 'lastmod' => $settings['lastmod'] ?? date( 'Y-m-d', filemtime( $file ) ), 'video' => $settings['video'] ?? null ];
	if ( ! preg_match( '/\.html?$/i', $file ) ) { echo "  run time  $path (" . $settings['file'] . ")\n"; continue; }

	$html = file_get_contents( $file );
	if ( ! preg_match( '#(<!-- monospace-seo -->)(.*?)(<!-- /monospace-seo -->)#s', $html, $m ) ) {
		echo "  NO MARKER $path: add <!-- monospace-seo --><!-- /monospace-seo --> in <head>\n"; $errors++; continue;
	}
	$block = "<!-- monospace-seo -->\n" . monospace_seo_render( monospace_seo_static_page( $config, $path ) )['html'] . '<!-- /monospace-seo -->';
	$write( $file, str_replace( $m[0], $block, $html ), $path );
}

$write( $root . '/sitemap.xml', monospace_seo_sitemap_xml( $urls ), 'sitemap.xml (' . count( $urls ) . ' pages)' );

$robots = is_file( $root . '/robots.txt' ) ? file_get_contents( $root . '/robots.txt' ) : "User-agent: *\n";
if ( stripos( $robots, 'sitemap:' ) === false ) $robots = rtrim( $robots ) . "\n\nSitemap: $home/sitemap.xml\n";
$write( $root . '/robots.txt', $robots, 'robots.txt' );

exit( $errors ? 1 : 0 );
