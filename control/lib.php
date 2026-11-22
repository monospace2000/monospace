<?php
/**
 * monospace.com control - the shared code: settings, the spotlight CSS, publishing, login.
 *
 * Standalone (no WordPress, nothing from art.monospace.com). The control screen (index.php) edits what the home page
 * shows and writes it to ../home-config.json; the home page (../index.php) prints that file's CSS. No file = the page
 * as it always was. The password's hash and the login throttle live in ../../control-data, outside the web root.
 */

if (!defined('MS_CONTROL')) exit;

const MS_CONFIG_FILE = __DIR__ . '/../home-config.json';

/** The devices: key => [label, media query ('' = all), preview width, preview height] */
function ms_devices(): array {
    return [
        'desktop' => ['Computer', '',                   1280, 800],
        'tablet'  => ['Tablet',   '(max-width: 920px)', 820,  1080],
        'phone'   => ['Phone',    '(max-width: 500px)', 390,  780],
        'landscape' => ['Phone sideways', '(max-height: 500px) and (orientation: landscape)', 844, 390], // last: it wins over Tablet / Computer for a short, wide screen
    ];
}

/**
 * The spotlight - the art site's signature beam of light, ported (2026-10-28): a pool of light where it lands and, over
 * the rest of the page, a shade with a soft cone cut out of it - all CSS gradients driven by variables. By default the
 * light lands on the home page's spotlight image, coming in from the upper right. monospace.com is dark, so the pool
 * fades to the light's own colour at 0% (the haze shows only inside the beam) and the shade is its own colour.
 */
function ms_spotlight_defaults(): array {
    return [
        'on'    => 1,
        'pos'   => [ // across (vw), down (vh), bright core (%), reach (vw), light from (deg clockwise from straight up)
            'desktop' => [70, 52, 8, 60, 30],
            'tablet'  => [70, 52, 8, 80, 30],
            'phone'   => [50, 38, 8, 110, 30],
            'landscape' => [80, 52, 8, 50, 30],
        ],
        'tone'  => [ // per screen: light colour, light strength %, shade colour, shade strength %, half the cone's width (degrees)
            'desktop' => ['#8aa8ff', 60, '#000000', 55, 13],
            'tablet'  => ['#8aa8ff', 60, '#000000', 55, 13],
            'phone'   => ['#8aa8ff', 60, '#000000', 55, 13],
            'landscape' => ['#8aa8ff', 60, '#000000', 55, 13],
        ],
    ];
}

/** Saved (or posted) spotlight settings, filled in and clamped */
function ms_spotlight_settings(?array $s = null): array {
    $d = ms_spotlight_defaults();
    if ($s === null) $s = ms_config()['spotlight'] ?? [];
    $hex = fn($v, $f) => preg_match('/^#[0-9a-f]{6}$/i', (string) $v) ? strtolower($v) : $f;
    $pct = fn($v, $f) => is_numeric($v) ? max(0, min(100, (int) $v)) : $f;
    $out = ['on' => !isset($s['on']) || $s['on'] ? 1 : 0, 'pos' => [], 'tone' => []];
    // Saved before the colours and beam width were per screen: one set for all of them
    $old = isset($s['light'][0]) ? [$s['light'][0], $s['light'][1] ?? null, $s['shade'][0] ?? null, $s['shade'][1] ?? null, $s['beam'] ?? null] : null;
    foreach ($d['pos'] as $dev => $def) {
        $p = $s['pos'][$dev] ?? null;
        if (!is_array($p) || count($p) !== 5) { $out['pos'][$dev] = $dev === 'landscape' ? $out['pos']['desktop'] : $def; } // a screen added later starts like Computer (the light's place is in screen percentages)
        else {
            $p = array_map('intval', array_values($p));
            $out['pos'][$dev] = [max(0, min(100, $p[0])), max(0, min(100, $p[1])), max(0, min(100, $p[2])), max(10, min(250, $p[3])), (($p[4] % 360) + 360) % 360];
        }
        $t = $s['tone'][$dev] ?? null;
        if (!is_array($t) || count($t) !== 5) $t = $old ?? $d['tone'][$dev];
        $t = array_values($t); $f = $d['tone'][$dev];
        $out['tone'][$dev] = [$hex($t[0], $f[0]), $pct($t[1], $f[1]), $hex($t[2], $f[2]), $pct($t[3], $f[3]), is_numeric($t[4]) ? max(3, min(40, (int) $t[4])) : $f[4]];
        if ($dev !== 'desktop') { $out['tone'][$dev][0] = $out['tone']['desktop'][0]; $out['tone'][$dev][2] = $out['tone']['desktop'][2]; } // the colours are the same on every screen
    }
    return $out;
}

/** #rrggbb + 0-100 -> rgba() */
function ms_rgba(string $hex, $alpha): string {
    $h = ltrim($hex, '#');
    if (!preg_match('/^[0-9a-f]{6}$/i', $h)) $h = 'ffffff';
    return sprintf('rgba(%d, %d, %d, %s)', hexdec(substr($h, 0, 2)), hexdec(substr($h, 2, 2)), hexdec(substr($h, 4, 2)), round(max(0, min(100, (int) $alpha)) / 100, 2));
}

/** The CSS for the settings - what the home page prints. The preview in index.php builds the same in JS: keep the two in step. */
function ms_spotlight_css(array $s): string {
    if (!$s['on']) return ':root{--spot-pool:none}body::after{display:none}';
    $mask = 'conic-gradient(from calc(var(--beam-angle) + 180deg - var(--beam-half)) at var(--sx) var(--sy),'
          . '#000 0deg,rgba(0,0,0,.85) calc(var(--beam-half) * .3),rgba(0,0,0,.1) calc(var(--beam-half) * .8),transparent var(--beam-half),'
          . 'rgba(0,0,0,.1) calc(var(--beam-half) * 1.2),rgba(0,0,0,.85) calc(var(--beam-half) * 1.7),#000 calc(var(--beam-half) * 2)),'
          . 'radial-gradient(circle at var(--sx) var(--sy),transparent calc(var(--beam-len) * .9),#000 calc(var(--beam-len) + var(--gradient-radius) * 1.3))';
    $css = ':root{--beam-len:110vmax'
         . ';--spot-pool:radial-gradient(circle at var(--gradient-center-x) var(--gradient-center-y),var(--gradient-foreground) var(--gradient-start),var(--gradient-edge) var(--gradient-radius))}';
    foreach (ms_devices() as $dev => [, $media]) {
        [$x, $y, $core, $reach, $angle] = $s['pos'][$dev];
        [$lc, $la, $sc, $sa, $beam] = $s['tone'][$dev];
        $rule = ":root{--gradient-foreground:" . ms_rgba($lc, $la) . ';--gradient-edge:' . ms_rgba($lc, 0) . ';--beam-shade:' . ms_rgba($sc, $sa) . ";--beam-half:{$beam}deg"
              . ";--gradient-center-x:{$x}vw;--gradient-center-y:{$y}vh;--gradient-start:{$core}%;--gradient-radius:{$reach}vw;--beam-angle:{$angle}deg}";
        $css .= $media ? "@media $media{" . $rule . '}' : $rule;
    }
    return $css . 'body::after{content:"";position:fixed;inset:0;z-index:-2;pointer-events:none;'
         . '--sx:calc(var(--gradient-center-x) + var(--beam-len) * sin(var(--beam-angle)));'
         . '--sy:calc(var(--gradient-center-y) - var(--beam-len) * cos(var(--beam-angle)));'
         . 'background:linear-gradient(var(--beam-shade),var(--beam-shade));-webkit-mask-image:' . $mask . ';mask-image:' . $mask . '}';
}

/** What is published now (the decoded file), or [] */
function ms_config(): array {
    $j = is_readable(MS_CONFIG_FILE) ? json_decode((string) file_get_contents(MS_CONFIG_FILE), true) : null;
    return is_array($j) ? $j : [];
}

/** Publish: merge the given parts (spotlight / panels / look) into home-config.json, with the CSS built from them. Returns true or an error message. */
function ms_publish(array $parts) {
    $cfg = ['version' => 1, 'updated' => gmdate('c')] + $parts + ms_config();
    if (isset($parts['spotlight'])) $cfg['spotlight_css'] = ms_spotlight_css($parts['spotlight']);
    if (isset($parts['look'])) $cfg['look_css'] = ms_look_css($parts['look']);
    if (isset($parts['panels'])) $cfg['panels_css'] = ms_panels_css($parts['panels']);
    if (isset($parts['site'])) { $cfg['logo_css'] = ms_logo_css($parts['site']); $cfg['oval_css'] = ms_oval_css($parts['site']); $cfg['contact_css'] = ms_contact_css($parts['site']); $cfg['tagline_css'] = ms_tagline_css($parts['site']); }
    $json = json_encode($cfg, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    $tmp = MS_CONFIG_FILE . '.tmp';
    if (file_put_contents($tmp, $json) === false || !rename($tmp, MS_CONFIG_FILE)) {
        @unlink($tmp);
        return 'Could not write home-config.json - check that the site folder is writable.';
    }
    return true;
}

/* ------------------------------------------------------------ panels ------------------------------------------------------------ */

const MS_IMAGE_DIR = 'assets/images/spotlight/';

function ms_defaults(): array { static $d; return $d ??= require __DIR__ . '/defaults.php'; }

/** The spotlight images that can be chosen (paths like assets/images/spotlight/hand.png; the blank oval, base.png, is the stand, not a choice) */
function ms_images(): array {
    $mine = $own = [];
    foreach (glob(__DIR__ . '/../' . MS_IMAGE_DIR . '*.png') ?: [] as $f) {
        if (basename($f) === 'base.png') continue;
        if (preg_match('/^(custom|obj)-[a-f0-9]{8}\.png$/', basename($f))) $mine[MS_IMAGE_DIR . basename($f)] = filemtime($f); else $own[] = MS_IMAGE_DIR . basename($f);
    }
    arsort($mine); // the ones made on the screen come first, the newest at the very front
    sort($own);
    return array_merge(array_keys($mine), $own);
}

/** A link that can't run script: http(s), a path, or a plain name like "wayback" */
function ms_safe_url(string $u): string {
    $u = trim($u);
    if (preg_match('/^\s*(javascript|data|vbscript):/i', $u)) return '';
    return preg_match('/^(https?:\/\/[^\s<>"]+|\/?[A-Za-z0-9_.~\/%#?&=+-]*)$/', $u) ? $u : '';
}

/** The panels, cleaned: unique safe ids, short texts, known images only. At least one panel - otherwise the starting ones. */
function ms_panels_settings($in): array {
    $images = ms_images(); $out = []; $seen = [];
    foreach (is_array($in) ? array_slice($in, 0, 40) : [] as $p) {
        if (!is_array($p)) continue;
        $id = preg_replace('/[^a-z0-9_-]/', '', strtolower((string) ($p['id'] ?? '')));
        if ($id === '' || isset($seen[$id])) $id = 'p' . bin2hex(random_bytes(4));
        $seen[$id] = 1;
        $img = (string) ($p['image'] ?? '');
        $out[] = [
            'id'    => $id,
            'title' => mb_substr(trim((string) ($p['title'] ?? '')), 0, 80),
            'text'  => mb_substr(str_replace("\r", '', (string) ($p['text'] ?? '')), 0, 1200),
            'url'   => ms_safe_url((string) ($p['url'] ?? '')),
            'image' => in_array($img, $images, true) ? $img : '',
            'clear' => !empty($p['clear']),
            'show'  => !isset($p['show']) || $p['show'],
            'label' => mb_substr(trim((string) ($p['label'] ?? '')), 0, 160),
        ] + (is_array($p['look'] ?? null) && $p['look'] ? ['look' => ms_panel_look($p['look'])] : []);
    }
    return $out ?: ms_defaults()['panels'];
}

/** What the page shows now: the published panels, else the starting ones */
function ms_panels_now(): array { $c = ms_config(); return isset($c['panels']) ? ms_panels_settings($c['panels']) : ms_defaults()['panels']; }

/* ------------------------------------------------------------ the panels' look ------------------------------------------------------------ */

function ms_look_settings($in = null): array {
    $d = ms_defaults()['look'];
    if ($in === null) $in = ms_config()['look'] ?? [];
    $in = is_array($in) ? $in : [];
    $n = fn($k, $lo, $hi) => isset($in[$k]) && is_numeric($in[$k]) ? max($lo, min($hi, (int) $in[$k])) : $d[$k];
    $hex = fn($v, $f) => preg_match('/^#[0-9a-f]{6}$/i', (string) $v) ? strtolower($v) : $f;
    return [
        'widths' => ms_look_widths($in), 'radius' => $n('radius', 0, 40), 'blur' => $n('blur', 0, 30),
        'tint' => [$hex($in['tint'][0] ?? '', $d['tint'][0]), isset($in['tint'][1]) && is_numeric($in['tint'][1]) ? max(0, min(95, (int) $in['tint'][1])) : $d['tint'][1]],
        'border' => $n('border', 0, 100), 'shine' => $n('shine', 0, 100), 'glow' => $n('glow', 0, 100),
        'sizes' => ms_look_sizes($in),
        'lefts' => ms_look_lefts($in),
        'color' => $hex($in['color'] ?? '', $d['color']),
    ];
}

/** The panel width per screen: [% of the screen, widest px]. Saved before they were per screen: width / max, for computer and tablet (the phone keeps its 90%). */
function ms_look_widths(array $in): array {
    $d = ms_defaults()['look']['widths'];
    $out = [];
    foreach ($d as $dev => $def) {
        $p = $in['widths'][$dev] ?? null;
        if (!is_array($p) || count($p) !== 2) $p = in_array($dev, ['phone', 'landscape'], true) ? $def : [$in['width'] ?? $def[0], $in['max'] ?? $def[1]];
        $p = array_values($p);
        $out[$dev] = [is_numeric($p[0]) ? max(15, min(100, (int) $p[0])) : $def[0], is_numeric($p[1]) ? max(250, min(900, (int) $p[1])) : $def[1]];
    }
    return $out;
}

/** The panels' left edge per screen (% of the screen's width) */
function ms_look_lefts(array $in): array {
    $out = [];
    foreach (ms_defaults()['look']['lefts'] as $dev => $def) {
        $v = $in['lefts'][$dev] ?? null;
        $out[$dev] = is_numeric($v) ? max(0, min(60, (int) $v)) : $def;
    }
    return $out;
}

/** The panel title / text sizes per screen (px). Saved before they were per screen: title_size / text_size, the same on all. */
function ms_look_sizes(array $in): array {
    $d = ms_defaults()['look']['sizes'];
    $out = [];
    foreach ($d as $dev => $def) {
        $p = $in['sizes'][$dev] ?? null;
        if (!is_array($p) || count($p) !== 2) $p = $dev === 'landscape' ? $def : [$in['title_size'] ?? $def[0], $in['text_size'] ?? $def[1]];
        $p = array_values($p);
        $out[$dev] = [is_numeric($p[0]) ? max(14, min(48, (int) $p[0])) : $def[0], is_numeric($p[1]) ? max(12, min(30, (int) $p[1])) : $def[1]];
    }
    return $out;
}

/** The CSS for the panels' look (variables index.php's CSS reads). The preview in index.php builds the same in JS: keep the two in step. */
function ms_look_css(array $l): string {
    $css = ':root{--panel-radius:' . $l['radius'] . 'px;--panel-blur:' . $l['blur'] . 'px'
        . ';--panel-tint:' . ms_rgba($l['tint'][0], $l['tint'][1]) . ';--panel-border-a:' . round($l['border'] / 100, 2) . ';--panel-shine:' . round($l['shine'] / 100, 2)
        . ';--panel-glow-a:' . round($l['glow'] / 100, 2) . ';--panel-color:' . $l['color'] . '}';
    foreach (ms_devices() as $dev => [, $media]) {
        [$w, $max] = $l['widths'][$dev];
        $rule = ':root{--panel-w:clamp(250px,' . $w . 'vw,' . $max . 'px);--panel-title-size:' . $l['sizes'][$dev][0] . 'px;--panel-text-size:' . $l['sizes'][$dev][1] . 'px}';
        if ($dev === 'phone' && $l['widths']['phone'] != ms_defaults()['look']['widths']['phone']) $rule .= '.panel-wrap{width:var(--panel-w)!important}'; // the page's own phone rule is 90%
        if ($l['lefts'][$dev] != ms_defaults()['look']['lefts'][$dev]) $rule .= '.section{justify-content:flex-start!important;box-sizing:border-box;padding-left:' . $l['lefts'][$dev] . 'vw!important}'; // only when moved: the page's own rules stay as they were
        $css .= $media ? "@media $media{" . $rule . '}' : $rule;
    }
    return $css;
}

/** A panel's own look: only the parts it sets (width, max, radius, tint, color, title_size, text_size) */
function ms_panel_look(array $in): array {
    $d = ms_defaults()['look'];
    $n = fn($k, $lo, $hi) => isset($in[$k]) && is_numeric($in[$k]) ? max($lo, min($hi, (int) $in[$k])) : null;
    $hex = fn($v) => preg_match('/^#[0-9a-f]{6}$/i', (string) $v) ? strtolower($v) : null;
    $out = array_filter([
        'width' => $n('width', 15, 80), 'max' => $n('max', 250, 900), 'radius' => $n('radius', 0, 40),
        'title_size' => $n('title_size', 14, 48), 'text_size' => $n('text_size', 12, 30), 'color' => $hex($in['color'] ?? ''),
    ], fn($v) => $v !== null);
    if (is_array($in['tint'] ?? null) && ($h = $hex($in['tint'][0] ?? '')) !== null && is_numeric($in['tint'][1] ?? null)) $out['tint'] = [$h, max(0, min(95, (int) $in['tint'][1]))];
    return $out;
}

/** CSS for the panels that have their own look: the same variables as the common look, set on that panel only */
function ms_panels_css(array $panels): string {
    $css = '';
    foreach ($panels as $p) {
        $l = $p['look'] ?? null;
        if (!$l || !preg_match('/^[a-z0-9_-]+$/', $p['id'])) continue;
        $v = ''; $sz = ''; // $sz: the panel's own width and text sizes - a phone on its side uses its own screen's instead (they are too big there)
        if (isset($l['width']) || isset($l['max'])) $sz .= '--panel-w:clamp(250px,' . ($l['width'] ?? ms_defaults()['look']['widths']['desktop'][0]) . 'vw,' . max(250, $l['max'] ?? ms_defaults()['look']['widths']['desktop'][1]) . 'px);';
        if (isset($l['radius'])) $v .= '--panel-radius:' . $l['radius'] . 'px;';
        if (isset($l['tint'])) $v .= '--panel-tint:' . ms_rgba($l['tint'][0], $l['tint'][1]) . ';';
        if (isset($l['title_size'])) $sz .= '--panel-title-size:' . $l['title_size'] . 'px;';
        if (isset($l['text_size'])) $sz .= '--panel-text-size:' . $l['text_size'] . 'px;';
        if (isset($l['color'])) $v .= '--panel-color:' . $l['color'] . ';';
        if ($v !== '') $css .= '#' . $p['id'] . '{' . rtrim($v, ';') . '}';
        $own = '';
        if ($sz !== '') $own .= '#' . $p['id'] . '{' . rtrim($sz, ';') . '}';
        if (!empty($p['clear']) && isset($l['text_size'])) $own .= '#' . $p['id'] . ' .clear-text{font-size:' . $l['text_size'] . 'px}'; // a text-only panel has no .panel-content
        if ($own !== '') $css .= '@media not all and ' . ms_devices()['landscape'][1] . '{' . $own . '}';
    }
    return $css;
}

/**
 * Where the logo sits in the layers (its z-index). 'behind' = as it always was: part of the light's layer, so the beam's shade dims it.
 * 'light' = its own layer above the light (-1), under the panels. 'front' = above the panels and the spotlight image (50; clicks pass through).
 */
function ms_logo_css(array $site): string {
    $d = ms_defaults()['site'];
    $behind = $site['logo_layer'] === 'behind';
    $custom = $site['logo_src'] != $d['logo_src'] || $site['logo_pos'] != $d['logo_pos'];
    if ($behind && !$custom) return '';
    $url = "url('" . $site['logo_src'] . "')";
    $css = $behind ? "body::before{background-image:$url,var(--spot-pool)}"
         : 'body::before{background-image:var(--spot-pool);background-position:0 0;background-size:100%}'
           . '#logo-layer{display:block;z-index:' . ($site['logo_layer'] === 'front' ? 50 : -1) . ";background-image:$url}";
    if ($custom) foreach (ms_devices() as $dev => [, $media]) { // the stylesheet's own positions only apply to the starting look
        [$x, $y, $size] = $site['logo_pos'][$dev];
        $rule = $behind ? "body::before{background-position:{$x}% {$y}vh,0 0;background-size:{$size}%,100%}" : "#logo-layer{background-position:{$x}% {$y}vh;background-size:{$size}%}";
        $css .= $media ? "@media $media{" . $rule . '}' : $rule;
    }
    return $css;
}

/**
 * The fixed oval behind the spotlight image. The images carry their own white oval (320 x 120 at the bottom of the 320 x 720 frame) and the whole
 * image fades between panels, oval included. With this on, a white oval of the same place and shape, $grow image-px larger all round, stays put behind
 * (index.php: .spotlight-oval) and only the figure fades. Sizes are percentages of the frame, so it scales with the image.
 */
function ms_oval_css(array $site): string {
    return empty($site['oval']) ? '' : '.spotlight-oval{display:block;--oval-grow:' . (int) $site['oval_grow'] . '}';
}

/**
 * The contact link as a button, copied from the art site (Look > Buttons: Glass / Flat / Outline, its colours, and Rounding > Buttons).
 * Same rules as button-glass-var / -flat-var / -outline-var in the theme's _button-styles.scss, at the size of this page's link.
 * 'plain' = no CSS: the white link as it always was.
 */
function ms_contact_css(array $site): string {
    $style = $site['contact_style'];
    if ($style === 'plain') return '';
    $c = $site['contact_custom'];
    $color = ['blue' => 'rgba(4,107,210,0.9)', 'black' => 'rgba(0,0,0,0.9)', 'grey' => '#aaa', 'custom' => $c][$site['contact_color']];
    $h = ltrim($c, '#');
    $text = $site['contact_color'] === 'custom' && (0.299 * hexdec(substr($h, 0, 2)) + 0.587 * hexdec(substr($h, 2, 2)) + 0.114 * hexdec(substr($h, 4, 2))) / 255 > 0.62 ? '#111' : '#fff';
    $radius = $site['contact_radius'] === 'pill' ? '9999px' : (int) $site['contact_radius'] . 'px';
    $a = '#contact-link a';
    $css = "$a{--btn-color:$color;--btn-text:$text;position:relative;display:inline-block;padding:7px 16px;font-family:'Open Sans',-apple-system,sans-serif;font-weight:600;line-height:1.4;border-radius:$radius;text-align:center;white-space:nowrap;transition:background-image .15s ease,box-shadow .15s ease,transform .05s ease}";
    if ($style === 'glass') {
        $css .= "$a{color:var(--btn-text);background-color:var(--btn-color);background-image:linear-gradient(to top,rgba(255,255,255,.35) 0%,rgba(255,255,255,.15) 45%,rgba(0,0,0,.05) 55%,rgba(0,0,0,.18) 100%);box-shadow:inset 0 1px 0 rgba(255,255,255,.55),inset 0 -1px 0 rgba(255,255,255,.3),0 2px 3px rgba(0,0,0,.25)}"
              . "$a::before{content:'';position:absolute;top:2px;left:7px;right:7px;height:45%;border-radius:inherit;background:linear-gradient(to bottom,rgba(255,255,255,.7),rgba(255,255,255,0));pointer-events:none}"
              . "$a:hover{background-color:color-mix(in srgb,var(--btn-color),white 12%);transform:scale(1.02);background-image:linear-gradient(to top,rgba(255,255,255,.55) 0%,rgba(255,255,255,.3) 45%,rgba(255,255,255,.05) 60%,rgba(0,0,0,.12) 100%);box-shadow:inset 0 1px 0 rgba(255,255,255,.45),inset 0 -1px 0 rgba(0,0,0,.25),0 4px 6px rgba(0,0,0,.2)}"
              . "$a:active{background-color:color-mix(in srgb,var(--btn-color),black 10%);transform:translateY(1px);box-shadow:inset 0 2px 3px rgba(255,255,255,.35),inset 0 -1px 2px rgba(0,0,0,.25)}";
    } elseif ($style === 'flat') {
        $css .= "$a{color:var(--btn-text);background-color:var(--btn-color);box-shadow:0 2px 4px rgba(0,0,0,.1)}$a:hover{background-color:color-mix(in srgb,var(--btn-color),white 12%)}$a:active{background-color:color-mix(in srgb,var(--btn-color),black 8%)}";
    } else {
        $css .= "$a{color:var(--btn-color);background-color:color-mix(in srgb,var(--btn-color),transparent 94%);border:2px solid var(--btn-color);padding:5px 14px}$a:hover{background-color:color-mix(in srgb,var(--btn-color),transparent 88%)}";
    }
    foreach (ms_devices() as $dev => [, $media]) {
        $rule = "$a{font-size:{$site['contact_size'][$dev]}px}";
        $css .= $media ? "@media $media{" . $rule . '}' : $rule;
    }
    return $css;
}

/** The tagline's fonts (the first is the page's own; the others are loaded by index.php already) and styles: key => [ label, CSS ] */
function ms_tagline_fonts(): array {
    return ['courier' => ['Courier New (the page\'s own)', "'Courier New',monospace"], 'opensans' => ['Open Sans', "'Open Sans',sans-serif"], 'oswald' => ['Oswald', "'Oswald',sans-serif"],
            'roboto' => ['Roboto', "'Roboto',sans-serif"], 'georgia' => ['Georgia', "Georgia,'Times New Roman',serif"]];
}
function ms_tagline_styles(): array {
    return ['normal' => ['Normal', 'font-weight:400'], 'italic' => ['Italic', 'font-weight:400;font-style:italic'], 'bold' => ['Bold', 'font-weight:700'],
            'bolditalic' => ['Bold italic', 'font-weight:700;font-style:italic'], 'caps' => ['Spaced capitals', 'font-weight:400;text-transform:uppercase;letter-spacing:.2em'],
            'glow' => ['Glow', 'font-weight:400;text-shadow:0 0 .5em currentColor,0 0 1.2em currentColor']];
}

/**
 * The tagline (index.php: #tagline). Placed like the logo: "across" is a percentage that keeps the text inside the screen (0 = at the left gutter, 50 = centred,
 * 100 = at the right gutter, whatever its width); it wraps rather than leave the screen. Under the panels (z-index 0, before them in the page). '' = off.
 */
function ms_tagline_css(array $site): string {
    if (empty($site['tagline_show']) || trim((string) $site['tagline_text']) === '') return '';
    $font = ms_tagline_fonts()[$site['tagline_font']][1];
    $style = ms_tagline_styles()[$site['tagline_style']][1];
    $css = "#tagline{display:block;position:fixed;z-index:0;margin:0;max-width:92vw;pointer-events:none;line-height:1.3;font-family:$font;color:{$site['tagline_color']};$style}";
    foreach (ms_devices() as $dev => [, $media]) {
        [$x, $y, $size] = $site['tagline_pos'][$dev];
        $align = $x < 34 ? 'left' : ($x > 66 ? 'right' : 'center');
        $left = round(0.92 * $x, 2);
        $rule = "#tagline{left:calc(4vw + {$left}vw);transform:translateX(-{$x}%);top:{$y}vh;font-size:{$size}px;text-align:$align}";
        $css .= $media ? "@media $media{" . $rule . '}' : $rule;
    }
    return $css;
}

/** A number within a range (one decimal), or the default */
function ms_num($v, float $min, float $max, $default) {
    return is_numeric($v) ? round(max($min, min($max, (float) $v)), 1) + 0 : $default;
}

/** The logo's picture: a path on this site or an https address, with nothing that could break out of url('...'). '' if not acceptable. */
function ms_logo_src(string $u): string {
    $u = trim($u);
    return preg_match('~^(https://[A-Za-z0-9_./%@:+=&?#,-]+|/?[A-Za-z0-9_./%+-]+)$~', $u) && strpos($u, '..') === false ? (strpos($u, 'https://') === 0 || $u[0] === '/' ? $u : '/' . $u) : '';
}

/** Save an uploaded logo picture as-is (PNG / JPEG / WebP / GIF, checked) in assets/images/logo/. Returns its path or an error starting with "!". */
function ms_save_logo(string $tmp) {
    $info = @getimagesize($tmp);
    $ext = [IMAGETYPE_PNG => 'png', IMAGETYPE_JPEG => 'jpg', IMAGETYPE_WEBP => 'webp', IMAGETYPE_GIF => 'gif'][$info[2] ?? 0] ?? null;
    if (!$ext) return '!That is not a PNG, JPEG, WebP or GIF picture.';
    $dir = __DIR__ . '/../assets/images/logo/';
    if (!is_dir($dir) && !@mkdir($dir, 0755, true)) return '!Could not make the logo folder.';
    $name = 'logo-' . bin2hex(random_bytes(4)) . '.' . $ext;
    return @move_uploaded_file($tmp, $dir . $name) ? '/assets/images/logo/' . $name : '!Could not save the picture (is the folder writable?).';
}

/** The page around the panels: contact link and footer line */
function ms_site_settings($in = null): array {
    $d = ms_defaults()['site'];
    if ($in === null) $in = ms_config()['site'] ?? [];
    $in = is_array($in) ? $in : [];
    $logoPos = []; $contactSize = []; $taglinePos = [];
    foreach ($d['logo_pos'] as $dev => $def) {
        $p = $in['logo_pos'][$dev] ?? null;
        if (!is_array($p) || count($p) !== 3) $p = isset($in['logo_x']) && $dev !== 'landscape' ? [$in['logo_x'], $in['logo_y'] ?? $def[1], $in['logo_size'] ?? $def[2]] : $def; // saved when the logo was one set for all screens
        $p = array_values($p);
        $logoPos[$dev] = [ms_num($p[0], 0, 100, $def[0]), ms_num($p[1], -20, 100, $def[1]), ms_num($p[2], 5, 100, $def[2])];
        $tp = isset($in['tagline_pos'][$dev]) && is_array($in['tagline_pos'][$dev]) && count($in['tagline_pos'][$dev]) === 3 ? array_values($in['tagline_pos'][$dev]) : $d['tagline_pos'][$dev];
        $taglinePos[$dev] = [ms_num($tp[0], 0, 100, $d['tagline_pos'][$dev][0]), ms_num($tp[1], -20, 100, $d['tagline_pos'][$dev][1]), ms_num($tp[2], 8, 120, $d['tagline_pos'][$dev][2])];
        $contactSize[$dev] = (int) ms_num($in['contact_size'][$dev] ?? null, 8, 40, $d['contact_size'][$dev]);
    }
    return [
        'contact_show' => !isset($in['contact_show']) || $in['contact_show'],
        'contact_text' => mb_substr(trim((string) ($in['contact_text'] ?? $d['contact_text'])), 0, 40) ?: $d['contact_text'],
        'contact_url'  => ms_safe_url((string) ($in['contact_url'] ?? $d['contact_url'])) ?: $d['contact_url'],
        'footer'       => mb_substr(trim((string) ($in['footer'] ?? $d['footer'])), 0, 200),
        'oval'         => !empty($in['oval']),
        'oval_grow'    => isset($in['oval_grow']) && is_numeric($in['oval_grow']) ? max(0, min(8, (int) $in['oval_grow'])) : $d['oval_grow'],
        'contact_style'  => in_array($in['contact_style'] ?? '', ['plain', 'glass', 'flat', 'outline'], true) ? $in['contact_style'] : $d['contact_style'],
        'contact_color'  => in_array($in['contact_color'] ?? '', ['blue', 'black', 'grey', 'custom'], true) ? $in['contact_color'] : $d['contact_color'],
        'contact_custom' => preg_match('/^#[0-9a-fA-F]{6}$/', (string) ($in['contact_custom'] ?? '')) ? strtolower($in['contact_custom']) : $d['contact_custom'],
        'contact_radius' => isset($in['contact_radius']) && is_numeric($in['contact_radius']) ? max(0, min(30, (int) $in['contact_radius'])) : $d['contact_radius'],
        'logo_src'     => ms_logo_src((string) ($in['logo_src'] ?? '')) ?: $d['logo_src'],
        'logo_pos'     => $logoPos,
        'contact_size' => $contactSize,
        'tagline_show'  => !empty($in['tagline_show']),
        'tagline_text'  => mb_substr(trim(preg_replace('/\s+/', ' ', (string) ($in['tagline_text'] ?? $d['tagline_text']))), 0, 160),
        'tagline_font'  => isset(ms_tagline_fonts()[$in['tagline_font'] ?? '']) ? $in['tagline_font'] : $d['tagline_font'],
        'tagline_color' => preg_match('/^#[0-9a-fA-F]{6}$/', (string) ($in['tagline_color'] ?? '')) ? strtolower($in['tagline_color']) : $d['tagline_color'],
        'tagline_style' => isset(ms_tagline_styles()[$in['tagline_style'] ?? '']) ? $in['tagline_style'] : $d['tagline_style'],
        'tagline_pos'   => $taglinePos,
        'logo_layer'   => in_array($in['logo_layer'] ?? '', ['behind', 'light', 'front'], true) ? $in['logo_layer'] : $d['logo_layer'],
    ];
}

/* ------------------------------------------------------------ spotlight objects ------------------------------------------------------------ */

/*
 * A spotlight object = an uploaded cut-out (kept in assets/images/spotlight/src/obj-xxxxxxxx.png) + its settings (obj-xxxxxxxx.json beside it).
 * The 320 x 720 spotlight image (assets/images/spotlight/obj-xxxxxxxx.png) is generated from them with GD: the white oval at the bottom
 * (base.png), the cut-out's silhouette filled black as the shadow - cut off by the oval's shape - and the cut-out on top.
 *   floating  hovers above the oval; the shadow is its silhouette seen from above, flattened, in the middle of the oval
 *   standing  stands on the oval (feet at the middle of it); the shadow is cast from the feet, flattened, leaning to one side
 * Each of the picture and the shadow can be nudged (across, down, rotation) and resized.
 */
const MS_OBJ_DIR = 'assets/images/spotlight/';
const MS_OBJ_W = 320;
const MS_OBJ_H = 720;

function ms_object_defaults(string $mode): array {
    $d = ['mode' => $mode, 'scale' => 100, 'dx' => 0, 'dy' => 0, 'rot' => 0, 'sh_scale' => 100, 'sh_dx' => 0, 'sh_dy' => 0, 'sh_rot' => 0];
    return $d + ($mode === 'standing' ? ['sh_flat' => 12, 'sh_lean' => -20] : ['sh_flat' => 45, 'sh_lean' => 0]) ;
}

/** Settings from anything posted: known keys only, clamped; what is missing comes from the mode's defaults */
function ms_object_settings($in, ?string $mode = null): array {
    $in = is_array($in) ? $in : [];
    $mode = in_array($in['mode'] ?? $mode, ['floating', 'standing'], true) ? ($in['mode'] ?? $mode) : 'floating';
    $d = ms_object_defaults($mode);
    $lim = ['scale' => [10, 300], 'dx' => [-320, 320], 'dy' => [-720, 720], 'rot' => [-180, 180], 'sh_scale' => [10, 300], 'sh_dx' => [-320, 320], 'sh_dy' => [-200, 200],
            'sh_rot' => [-180, 180], 'sh_flat' => [3, 100], 'sh_lean' => [-150, 150]];
    foreach ($lim as $k => [$lo, $hi]) $d[$k] = isset($in[$k]) && is_numeric($in[$k]) ? (int) round(max($lo, min($hi, (float) $in[$k]))) : $d[$k];
    return $d;
}

/** Load a picture file as a truecolor GD image with alpha; null if it is not a picture we take */
function ms_load_picture(string $file) {
    $info = @getimagesize($file);
    $f = [IMAGETYPE_PNG => 'imagecreatefrompng', IMAGETYPE_JPEG => 'imagecreatefromjpeg', IMAGETYPE_WEBP => 'imagecreatefromwebp', IMAGETYPE_GIF => 'imagecreatefromgif'][$info[2] ?? 0] ?? null;
    if (!$f || $info[0] * $info[1] > 40000000) return null;
    $im = @$f($file);
    if (!$im) return null;
    if (!imageistruecolor($im)) imagepalettetotruecolor($im);
    imagesavealpha($im, true);
    return $im;
}

/** The picture on the canvas, turned $deg degrees clockwise about its point $px, $py - that point landing on $cx, $cy */
function ms_place_turned($canvas, $pic, float $deg, float $px, float $py, float $cx, float $cy): void {
    $w = imagesx($pic); $h = imagesy($pic);
    $img = $pic;
    if (abs($deg) > 0.01) {
        imagealphablending($pic, false); imagesavealpha($pic, true);
        $img = imagerotate($pic, -$deg, imagecolorallocatealpha($pic, 0, 0, 0, 127));
        if (!$img) return;
        imagealphablending($img, false); imagesavealpha($img, true);
    }
    $r = deg2rad($deg); $c = cos($r); $s = sin($r);
    $vx = $w / 2 - $px; $vy = $h / 2 - $py; // from the pivot to the picture's middle, turned with it
    $mx = $cx + $c * $vx - $s * $vy; $my = $cy + $s * $vx + $c * $vy;
    imagealphablending($canvas, true);
    imagecopy($canvas, $img, (int) round($mx - imagesx($img) / 2), (int) round($my - imagesy($img) / 2), 0, 0, imagesx($img), imagesy($img));
}

/**
 * Lay the picture's silhouette as a black shadow on the oval: the silhouette transformed by $m = [a, b, c, d] (x' = a x + c y, y' = b x + d y) about its
 * point $ax, $ay, that point landing on $cx, $cy - and only where the oval (centre 160, 660; radii 160, 60) is. Soft edges (3 x 3 samples a pixel).
 */
function ms_shadow_on_oval($canvas, $pic, array $m, float $ax, float $ay, float $cx, float $cy): void {
    [$a, $b, $c, $d] = $m;
    $det = $a * $d - $b * $c;
    if (abs($det) < 1e-6) return;
    $w = imagesx($pic); $h = imagesy($pic);
    $cov = []; // the silhouette's coverage 0..1, row by row
    for ($y = 0; $y < $h; $y++) { $row = []; for ($x = 0; $x < $w; $x++) $row[] = (127 - ((imagecolorat($pic, $x, $y) >> 24) & 127)) / 127; $cov[] = $row; }
    $sample = function (float $x, float $y) use ($cov, $w, $h): float { // bilinear, pixel centres at +.5
        $x -= .5; $y -= .5; $x0 = (int) floor($x); $y0 = (int) floor($y); $fx = $x - $x0; $fy = $y - $y0; $v = 0.0;
        foreach ([[0, 0, (1 - $fx) * (1 - $fy)], [1, 0, $fx * (1 - $fy)], [0, 1, (1 - $fx) * $fy], [1, 1, $fx * $fy]] as [$i, $j, $wt]) {
            $xx = $x0 + $i; $yy = $y0 + $j;
            if ($xx >= 0 && $yy >= 0 && $xx < $w && $yy < $h) $v += $cov[$yy][$xx] * $wt;
        }
        return $v;
    };
    $xs = $ys = [];
    foreach ([[0, 0], [$w, 0], [0, $h], [$w, $h]] as [$x, $y]) { $x -= $ax; $y -= $ay; $xs[] = $cx + $a * $x + $c * $y; $ys[] = $cy + $b * $x + $d * $y; }
    $x1 = max(0, (int) floor(min($xs)) - 1); $x2 = min(MS_OBJ_W - 1, (int) ceil(max($xs)) + 1);
    $y1 = max(600, (int) floor(min($ys)) - 1); $y2 = min(MS_OBJ_H - 1, (int) ceil(max($ys)) + 1);
    $off = [-1 / 3, 0, 1 / 3];
    for ($Y = $y1; $Y <= $y2; $Y++) for ($X = $x1; $X <= $x2; $X++) {
        $px = ($X + .5 - 160) / 160; $py = ($Y + .5 - 660) / 60; $rho = sqrt($px * $px + $py * $py);
        $g = $rho > 0 ? sqrt(($px / 160) ** 2 + ($py / 60) ** 2) : 1 / 60;
        $oval = max(0, min(1, .5 + (1 - $rho) / $g)); // how much of this pixel is inside the oval
        if ($oval <= 0) continue;
        $sum = 0.0;
        foreach ($off as $oy) foreach ($off as $ox) {
            $u = $X + .5 + $ox - $cx; $v = $Y + .5 + $oy - $cy;
            $sum += $sample($ax + ($d * $u - $c * $v) / $det, $ay + (-$b * $u + $a * $v) / $det);
        }
        $k = $sum / 9 * $oval;
        if ($k <= 0.004) continue;
        $p = imagecolorat($canvas, $X, $Y); $al = ($p >> 24) & 127;
        $g2 = (int) round((($p >> 16) & 255) * (1 - $k)); // the oval is white; darken it
        imagesetpixel($canvas, $X, $Y, ($al << 24) | ($g2 << 16) | ($g2 << 8) | $g2);
    }
}

/** The generated 320 x 720 spotlight image (a GD image) for a cut-out and its settings */
function ms_render_object($src, array $o) {
    $W = MS_OBJ_W; $H = MS_OBJ_H; $standing = $o['mode'] === 'standing';
    [$w, $h] = [imagesx($src), imagesy($src)];
    $feet = 650; // where a standing figure's feet are (the oval is 600 - 720, its middle 660)
    $k = ($standing ? min(($W - 20) / $w, ($feet - 10) / $h) : min(($W - 40) / $w, 440 / $h)) * $o['scale'] / 100;
    [$nw, $nh] = [max(1, (int) round($w * $k)), max(1, (int) round($h * $k))];
    $pic = imagecreatetruecolor($nw, $nh);
    imagealphablending($pic, false); imagesavealpha($pic, true);
    imagefill($pic, 0, 0, imagecolorallocatealpha($pic, 0, 0, 0, 127));
    imagecopyresampled($pic, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);

    $canvas = imagecreatetruecolor($W, $H);
    imagealphablending($canvas, false); imagesavealpha($canvas, true);
    imagefill($canvas, 0, 0, imagecolorallocatealpha($canvas, 0, 0, 0, 127));

    // 1. the white oval
    $base = @imagecreatefrompng(__DIR__ . '/../' . MS_OBJ_DIR . 'base.png');
    if ($base) { imagealphablending($canvas, false); imagecopy($canvas, $base, 0, $H - imagesy($base), 0, 0, imagesx($base), imagesy($base)); }
    else imagefilledellipse($canvas, 160, 660, 320, 120, imagecolorallocate($canvas, 255, 255, 255));

    // 2. the shadow: the silhouette laid on the ground, cut off by the oval
    $q = $o['sh_flat'] / 100; $ss = $o['sh_scale'] / 100; $r = deg2rad($o['sh_rot']); $cs = cos($r); $sn = sin($r);
    if ($standing) { // ground position = (x + lean * height, height) about the feet, turned in the ground plane, then flattened
        $lean = $o['sh_lean'] / 100;
        $m = [$ss * $cs, $ss * $q * $sn, $ss * (-$cs * $lean + $sn), $ss * $q * (-$sn * $lean - $cs)];
        ms_shadow_on_oval($canvas, $pic, $m, $nw / 2, $nh, 160 + $o['sh_dx'], $feet + $o['sh_dy']);
    } else { // seen from above: turned, then flattened
        $m = [$ss * $cs, $ss * $q * $sn, -$ss * $sn, $ss * $q * $cs];
        ms_shadow_on_oval($canvas, $pic, $m, $nw / 2, $nh / 2, 160 + $o['sh_dx'], 660 + $o['sh_dy']);
    }

    // 3. the picture: floating turns about its middle, standing about its feet
    if ($standing) ms_place_turned($canvas, $pic, $o['rot'], $nw / 2, $nh, 160 + $o['dx'], $feet + $o['dy']);
    else ms_place_turned($canvas, $pic, $o['rot'], $nw / 2, $nh / 2, 160 + $o['dx'], 300 + $o['dy']);
    return $canvas;
}

/** A copy of the picture without its empty margin (alpha under 7% counts as empty - stray soft pixels would move the object), and no bigger than 1000 px */
function ms_trim_picture($im) {
    [$w, $h] = [imagesx($im), imagesy($im)];
    if (max($w, $h) > 1000) {
        $k = 1000 / max($w, $h); [$nw, $nh] = [max(1, (int) round($w * $k)), max(1, (int) round($h * $k))];
        $small = imagecreatetruecolor($nw, $nh);
        imagealphablending($small, false); imagesavealpha($small, true);
        imagefill($small, 0, 0, imagecolorallocatealpha($small, 0, 0, 0, 127));
        imagecopyresampled($small, $im, 0, 0, 0, 0, $nw, $nh, $w, $h);
        $im = $small; [$w, $h] = [$nw, $nh];
    }
    $x1 = $w; $y1 = $h; $x2 = -1; $y2 = -1;
    for ($y = 0; $y < $h; $y++) for ($x = 0; $x < $w; $x++) if (((imagecolorat($im, $x, $y) >> 24) & 127) < 118) { if ($x < $x1) $x1 = $x; if ($x > $x2) $x2 = $x; if ($y < $y1) $y1 = $y; if ($y > $y2) $y2 = $y; }
    if ($x2 < 0) return null; // nothing in it
    $out = imagecreatetruecolor($x2 - $x1 + 1, $y2 - $y1 + 1);
    imagealphablending($out, false); imagesavealpha($out, true);
    imagefill($out, 0, 0, imagecolorallocatealpha($out, 0, 0, 0, 127));
    imagecopy($out, $im, 0, 0, $x1, $y1, $x2 - $x1 + 1, $y2 - $y1 + 1);
    return $out;
}

function ms_object_files(string $id): array {
    return ['png' => __DIR__ . '/../' . MS_OBJ_DIR . "obj-$id.png", 'src' => __DIR__ . '/../' . MS_OBJ_DIR . "src/obj-$id.png", 'json' => __DIR__ . '/../' . MS_OBJ_DIR . "src/obj-$id.json"];
}

/** Settings of every object made here: id => settings */
function ms_objects(): array {
    $out = [];
    foreach (glob(__DIR__ . '/../' . MS_OBJ_DIR . 'src/obj-*.json') ?: [] as $f) {
        if (!preg_match('/obj-([a-f0-9]{8})\.json$/', $f, $m)) continue;
        $out[$m[1]] = ms_object_settings(json_decode((string) @file_get_contents($f), true));
    }
    return $out;
}

/** Make the image file from the stored cut-out and these settings. Returns its path or an error message starting with "!". */
function ms_object_make(string $id, array $o) {
    $f = ms_object_files($id);
    $src = is_readable($f['src']) ? ms_load_picture($f['src']) : null;
    if (!$src) return '!That picture is missing.';
    $im = ms_render_object($src, $o);
    return @imagepng($im, $f['png'], 9) && @file_put_contents($f['json'], json_encode($o)) !== false ? MS_OBJ_DIR . "obj-$id.png" : '!Could not save the image (is the folder writable?).';
}

/** A new object from an uploaded picture. Returns [id, path, settings] or an error message starting with "!". */
function ms_object_new(string $tmp, string $mode) {
    $im = ms_load_picture($tmp);
    if (!$im) return '!That is not a PNG, JPEG, WebP or GIF picture.';
    $im = ms_trim_picture($im);
    if (!$im) return '!There is nothing in that picture (it is all transparent).';
    $dir = __DIR__ . '/../' . MS_OBJ_DIR . 'src/';
    if (!is_dir($dir) && !@mkdir($dir, 0755, true)) return '!Could not make the folder for the pictures.';
    $id = bin2hex(random_bytes(4));
    if (!@imagepng($im, ms_object_files($id)['src'], 9)) return '!Could not save the picture.';
    $o = ms_object_settings([], $mode);
    $r = ms_object_make($id, $o);
    return $r[0] === '!' ? $r : [$id, $r, $o];
}

/** Delete a custom image nothing uses. Returns true or an error message. */
function ms_delete_image(string $path) {
    if (!preg_match('~^' . preg_quote(MS_IMAGE_DIR, '~') . '(custom|obj)-([a-f0-9]{8})\.png$~', $path, $m)) return 'Only images made here can be deleted.';
    foreach (ms_panels_now() as $p) if ($p['image'] === $path) return 'A panel still uses that image.';
    if (!@unlink(__DIR__ . '/../' . $path)) return 'Could not delete it.';
    if ($m[1] === 'obj') foreach (['src', 'json'] as $k) @unlink(ms_object_files($m[2])[$k]); // the picture and settings it was made from
    return true;
}

/* ------------------------------------------------------------ login ------------------------------------------------------------ */

function ms_data_dir(): string { return dirname(__DIR__, 2) . '/control-data'; }

function ms_auth_hash(): string {
    $j = json_decode((string) @file_get_contents(ms_data_dir() . '/auth.json'), true);
    return is_array($j) ? (string) ($j['hash'] ?? '') : '';
}

function ms_set_password(string $password): bool {
    $dir = ms_data_dir();
    if (!is_dir($dir) && !mkdir($dir, 0700, true)) return false;
    return file_put_contents($dir . '/auth.json', json_encode(['hash' => password_hash($password, PASSWORD_DEFAULT), 'changed' => gmdate('c')]), LOCK_EX) !== false;
}

/** Failed logins in the last 10 minutes are counted for the whole site; 5 = locked until they age out */
function ms_throttle_fails(): array {
    $f = json_decode((string) @file_get_contents(ms_data_dir() . '/throttle.json'), true);
    return array_values(array_filter(is_array($f) ? $f : [], fn($t) => is_int($t) && $t > time() - 600));
}
function ms_throttle_add(): void {
    $f = ms_throttle_fails(); $f[] = time();
    @file_put_contents(ms_data_dir() . '/throttle.json', json_encode($f), LOCK_EX);
}
function ms_throttle_clear(): void { @unlink(ms_data_dir() . '/throttle.json'); }
function ms_locked(): bool { return count(ms_throttle_fails()) >= 5; }
