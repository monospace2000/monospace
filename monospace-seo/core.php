<?php
/**
 * monospace SEO: the engine. No WordPress needed.
 *
 * Shared by the two front ends:
 *   - monospace-seo.php (WordPress mu-plugin): works out the page from WordPress, then calls monospace_seo_render().
 *   - static.php (sites without WordPress): reads the site's monospace-seo.config.php, at run time for PHP pages or through
 *     build.php, which writes the tags into .html pages and writes sitemap.xml.
 * Master copy: knowledge base monospace2000/Claude, tools/monospace-seo/. Copy unchanged to every site.
 */

if ( defined( 'MONOSPACE_SEO_CORE' ) ) return;
define( 'MONOSPACE_SEO_CORE', '1.1.0' );

/** Escape for an HTML attribute or text. */
function monospace_seo_e( $value ) {
	return htmlspecialchars( (string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
}

/** Plain text from HTML: no tags, scripts, styles, headings, captions or extra whitespace. */
function monospace_seo_text( $html ) {
	$text = preg_replace( '#<(script|style|figcaption|h[1-6])[^>]*>.*?</\1>#is', ' ', (string) $html );
	$text = preg_replace( '#<br\s*/?>|</(p|div|li)>#i', ' ', $text );
	$text = strip_tags( $text );
	$text = html_entity_decode( $text, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
	return trim( preg_replace( '/\s+/u', ' ', $text ) );
}

/** Shorten to $max characters on a word boundary. */
function monospace_seo_trim( $text, $max = 158 ) {
	$text = trim( (string) $text );
	if ( mb_strlen( $text ) <= $max ) return $text;
	$cut   = mb_substr( $text, 0, $max - 1 );
	$space = mb_strrpos( $cut, ' ' );
	if ( $space > $max * 0.6 ) $cut = mb_substr( $cut, 0, $space );
	return rtrim( $cut, " ,;:.–-" ) . '…';
}

/** JSON-LD nodes every site shares: the person, an optional publisher and the website. */
function monospace_seo_base_graph( array $site ) {
	$home   = rtrim( $site['url'], '/' ) . '/';
	$graph  = [];
	$person = $site['person'] ?? null;
	if ( $person ) $graph[] = array_merge( [ '@type' => 'Person', '@id' => $home . '#person' ], $person );
	$publisher = $person ? [ '@id' => $home . '#person' ] : null;
	if ( ! empty( $site['publisher'] ) ) {
		$graph[]   = array_merge( [ '@type' => 'Organization', '@id' => $home . '#publisher' ], $site['publisher'] );
		$publisher = [ '@id' => $home . '#publisher' ];
	}
	$graph[] = [
		'@type'       => 'WebSite',
		'@id'         => $home . '#website',
		'url'         => $home,
		'name'        => $site['site_name'] ?? '',
		'description' => $site['description'] ?? '',
		'inLanguage'  => str_replace( '_', '-', $site['locale'] ?? 'en_US' ),
		'publisher'   => $publisher,
	];
	return $graph;
}

/** Drop empty values (recursively) so the JSON-LD carries no blanks. */
function monospace_seo_clean( $value ) {
	if ( ! is_array( $value ) ) return $value;
	$out = [];
	foreach ( $value as $k => $v ) {
		$v = monospace_seo_clean( $v );
		if ( $v === null || $v === '' || $v === [] ) continue;
		$out[ $k ] = $v;
	}
	return array_keys( $out ) === range( 0, count( $out ) - 1 ) ? array_values( $out ) : $out;
}

/**
 * The head tags for one page.
 *
 * $p: title, description, url (address of this page), canonical (printed when set), site_name, locale, type (og:type),
 *     image [url, width, height, type, alt], published, modified (ISO 8601, articles), robots (string, printed when set),
 *     graph (JSON-LD nodes), extra ([ [attr, key, value], ... ] more meta tags), print_title (bool),
 *     tags_filter (callable: gets and returns the tag list before printing; WordPress passes its monospace_seo_tags filter).
 * Returns [ 'tags' => [ [attr, key, value], ... ], 'html' => string ].
 */
function monospace_seo_render( array $p ) {
	$image = $p['image'] ?? null;
	$tags  = [ [ 'name', 'description', $p['description'] ?? '' ] ];
	if ( ! empty( $p['robots'] ) ) $tags[] = [ 'name', 'robots', $p['robots'] ];
	$tags = array_merge( $tags, [
		[ 'property', 'og:type', $p['type'] ?? 'website' ],
		[ 'property', 'og:site_name', $p['site_name'] ?? '' ],
		[ 'property', 'og:locale', $p['locale'] ?? 'en_US' ],
		[ 'property', 'og:title', $p['og_title'] ?? $p['title'] ?? '' ],
		[ 'property', 'og:description', $p['og_description'] ?? $p['description'] ?? '' ],
		[ 'property', 'og:url', $p['url'] ?? $p['canonical'] ?? '' ],
	] );
	if ( $image && ! empty( $image['url'] ) ) {
		$tags[] = [ 'property', 'og:image', $image['url'] ];
		$tags[] = [ 'property', 'og:image:width', $image['width'] ?? '' ];
		$tags[] = [ 'property', 'og:image:height', $image['height'] ?? '' ];
		$tags[] = [ 'property', 'og:image:type', $image['type'] ?? '' ];
		$tags[] = [ 'property', 'og:image:alt', $image['alt'] ?? '' ];
	}
	if ( ! empty( $p['published'] ) ) $tags[] = [ 'property', 'article:published_time', $p['published'] ];
	if ( ! empty( $p['modified'] ) )  $tags[] = [ 'property', 'article:modified_time', $p['modified'] ];
	$tags[] = [ 'name', 'twitter:card', $image && ! empty( $image['url'] ) ? 'summary_large_image' : 'summary' ];
	foreach ( $p['extra'] ?? [] as $tag ) $tags[] = $tag;
	if ( isset( $p['tags_filter'] ) && is_callable( $p['tags_filter'] ) ) $tags = call_user_func( $p['tags_filter'], $tags );

	$html = '';
	if ( ! empty( $p['print_title'] ) ) $html .= '<title>' . monospace_seo_e( $p['title'] ?? '' ) . "</title>\n";
	foreach ( $tags as [ $attr, $key, $value ] ) {
		if ( $value === '' || $value === null ) continue;
		$html .= '<meta ' . $attr . '="' . monospace_seo_e( $key ) . '" content="' . monospace_seo_e( $value ) . '">' . "\n";
	}
	if ( ! empty( $p['canonical'] ) ) $html .= '<link rel="canonical" href="' . monospace_seo_e( $p['canonical'] ) . '">' . "\n";
	if ( ! empty( $p['graph'] ) ) {
		$json  = json_encode( [ '@context' => 'https://schema.org', '@graph' => monospace_seo_clean( $p['graph'] ) ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
		$html .= '<script type="application/ld+json">' . str_replace( '</', '<\/', $json ) . "</script>\n";
	}
	return [ 'tags' => $tags, 'html' => $html ];
}

/**
 * sitemap.xml for a list of [ 'loc' => url, 'lastmod' => Y-m-d, 'video' => optional [ thumbnail, title, description,
 * player, duration (seconds), published (ISO 8601) ] ] (Google's video sitemap).
 */
function monospace_seo_sitemap_xml( array $urls ) {
	$has_video = (bool) array_filter( $urls, function ( $u ) { return ! empty( $u['video'] ); } );
	$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"'
		. ( $has_video ? ' xmlns:video="http://www.google.com/schemas/sitemap-video/1.1"' : '' ) . ">\n";
	foreach ( $urls as $u ) {
		$xml .= '  <url><loc>' . monospace_seo_e( $u['loc'] ) . '</loc>' . ( ! empty( $u['lastmod'] ) ? '<lastmod>' . monospace_seo_e( $u['lastmod'] ) . '</lastmod>' : '' );
		if ( ! empty( $u['video'] ) ) {
			$v = $u['video'];
			$xml .= "\n    <video:video>";
			foreach ( [ 'thumbnail_loc' => 'thumbnail', 'title' => 'title', 'description' => 'description', 'player_loc' => 'player', 'duration' => 'duration', 'publication_date' => 'published' ] as $tag => $key ) {
				if ( isset( $v[ $key ] ) && $v[ $key ] !== '' ) $xml .= "<video:$tag>" . monospace_seo_e( $v[ $key ] ) . "</video:$tag>";
			}
			$xml .= "<video:family_friendly>yes</video:family_friendly></video:video>\n  ";
		}
		$xml .= "</url>\n";
	}
	return $xml . "</urlset>\n";
}
