<?php
// The panels and their look come from home-config.json, published by the control screen (/control/); no file = the starting
// panels in control/defaults.php, exactly the page as it was. In preview (?ms_preview=1) the control screen can POST a draft.
$homeDefaults = require __DIR__ . '/control/defaults.php';
$homeConfig = json_decode((string) @file_get_contents(__DIR__ . '/home-config.json'), true);
if (!is_array($homeConfig)) $homeConfig = [];
$isPreview = isset($_GET['ms_preview']);
// The page changes whenever the control screen applies something: never let the CDN or a browser keep an old copy (2026-10-28: the host's CDN served a stale home page)
header($isPreview ? 'Cache-Control: no-store' : 'Cache-Control: no-cache, must-revalidate, max-age=0');
$draft = null;
if ($isPreview && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['draft'])) {
    define('MS_CONTROL', 1);
    require __DIR__ . '/control/lib.php';
    $draft = json_decode((string) $_POST['draft'], true);
    if (is_array($draft)) {
        $homeConfig['panels'] = ms_panels_settings($draft['panels'] ?? []);
        $homeConfig['panels_css'] = ms_panels_css($homeConfig['panels']);
        $homeConfig['site'] = ms_site_settings($draft['site'] ?? []);
        $homeConfig['logo_css'] = ms_logo_css($homeConfig['site']);
        $homeConfig['oval_css'] = ms_oval_css($homeConfig['site']);
        $homeConfig['contact_css'] = ms_contact_css($homeConfig['site']);
        $homeConfig['tagline_css'] = ms_tagline_css($homeConfig['site']);
    }
}
$homePanels = array_values(array_filter(is_array($homeConfig['panels'] ?? null) ? $homeConfig['panels'] : $homeDefaults['panels'], fn($p) => !isset($p['show']) || $p['show']));
if (!$homePanels) $homePanels = $homeDefaults['panels'];
$focusIndex = 0;
foreach ($homePanels as $i => $p) if (isset($draft['focus']) && $p['id'] === $draft['focus']) $focusIndex = $i;
$e = fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
$homeSite = ($homeConfig['site'] ?? []) + $homeDefaults['site'];
$safeUrl = fn($u) => preg_match('~^\s*(javascript|data|vbscript):~i', (string) $u) ? '' : (string) $u;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>monospace</title>
<meta name="description" content="monospace" />
<meta property="og:title" content="monospace" />
<meta property="og:description" content="Music, Art, and Interactive Multimedia since 1998. You are visitor number one." />
<meta property="og:site_name" content="monospace.com" />
<meta property="og:locale" content="en_US" />
<meta property="og:type" content="website" />
<meta property="og:url" content="https://www.monospace.com/" />
<meta property="og:image" content="https://www.monospace.com/assets/images/site_image.png" />
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:site" content="https://www.monospace.com" />
<meta name="twitter:creator" content="@monospace" />
<meta name="twitter:title" content="monospace" />
<meta name="twitter:description" content="Music, Art, and Interactive Multimedia since 1998. You are visitor number one." />
<meta name="twitter:image" content="https://www.monospace.com/assets/images/site_image.png" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@600&family=Oswald:wght@400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

<style>
/* Base styles */
html, body {
    margin: 0;
    font-family: "Courier New", monospace;
    font-size: 1.2em;
    color: white;
    height: 100vh;
    height: 100dvh;
    overflow: hidden;
    overscroll-behavior: none;
    scrollbar-width: none;
    background-color: #000;
}
/* The page never scrolls (the panels are moved by script): lock it, so rotating a phone cannot bring up scrollbars */
html { position: fixed; inset: 0; width: 100%; overflow: clip; }
/* A finger only moves the panels up and down: no sideways drag of the page on a phone (pinch-zoom stays) */
html, body { touch-action: pan-y pinch-zoom; overscroll-behavior: none; max-width: 100%; }
html::-webkit-scrollbar, body::-webkit-scrollbar { display: none; }
/* The page's light: --spot-pool is the glow behind the logo. The control screen (/control/)
   replaces it, and adds the beam (body::after), through the CSS in home-config.json - printed after this block. */
:root {
    --spot-pool: radial-gradient(ellipse 60% 40% at 20% 0vh, rgba(0,0,255,0.75) 0%, rgba(0,0,255,0) 55vw);
    /* The panels' look - the control screen replaces these through the CSS in home-config.json */
    --panel-w: clamp(250px, 30vw, 400px);
    --panel-radius: 16px;
    --panel-blur: 5px;
    --panel-tint: rgba(0,0,0,0.3);
    --panel-border-a: 0.3;
    --panel-shine: 1;
    --panel-glow-a: 0.5;
    --panel-title-size: 24px;
    --panel-text-size: 18px;
    --panel-color: white;
}
body {
    position: relative;
    z-index: 0; /* its own stacking context, so the light (z-index -2) sits above the black but below the panels */
}
body::before {
    content: '';
    position: fixed;
    inset: 0;
    z-index: -2;
    background-image: url('/assets/images/logo_xl_transp.png'), var(--spot-pool);
    background-position: 10% 10vh, 0 0;
    background-repeat: no-repeat;
    background-color: #000;
    background-size: 55%, 100%;
}

.section {
    position: absolute;
    width: 100%;
    height: 100vh;
    height: 100dvh;
    display: flex;
    justify-content: flex-start; /* moved left of center */
    align-items: center;
    padding-left: 10vw; /* left offset */
}

/* The tagline: hidden until the control screen's CSS (#ms-tagline) places and shows it */
#tagline { display: none; }

/* The logo as its own layer - only used when the control screen moves it out of the light's layer (z-index) */
.logo-layer {
    display: none;
    position: fixed;
    inset: 0;
    pointer-events: none;
    background: url('/assets/images/logo_xl_transp.png') 10% 10vh / 55% no-repeat;
}
@media (max-width: 500px) { .logo-layer { background-position: center 5vh; background-size: 90%; } }

/* panel panels */
.panel-wrap {
    width: 30vw;
    border-radius: var(--panel-radius);
}
.panel {
    box-shadow: 10px 10px 10px rgba(0,0,0,0.3),
                -10px 10px 10px rgba(0,0,0,0.3),
                inset 5px 5px 10px rgba(0,0,0,0.2),
                inset -5px -5px 10px rgba(0,0,0,0.2);
    background: linear-gradient(to bottom, rgba(255,255,255,calc(0.3 * var(--panel-shine))) 0px, rgba(255,255,255,0) 40px, rgba(255,255,255,0) 90%),
                linear-gradient(to top, rgba(255,255,255,calc(0.3 * var(--panel-shine))) 0px, rgba(255,255,255,0) 20px, rgba(255,255,255,calc(0.1 * var(--panel-shine))) 40%, rgba(255,255,255,0) 100%),
                var(--panel-tint);
    border-radius: var(--panel-radius);
    border: solid 1px rgba(255,255,255,var(--panel-border-a));
    color: var(--panel-color);
    cursor: pointer;
    backdrop-filter: blur(var(--panel-blur));
}
.panel-clear{
    box-shadow: none;
    background: none;
    border: none;
    cursor: default;
    backdrop-filter: none;
    padding-bottom:10vh !important;
}
.panel-top {
    height: 20px;
    margin: 4px 4px 0 4px;
    background: linear-gradient(rgba(255,255,255,calc(0.5 * var(--panel-shine))) 0px, rgba(255,255,255,0) 20px);
    border-radius: 10px 10px 0 0;
}
.panel-header {
    font-family: 'Oswald', sans-serif;
    font-size: var(--panel-title-size);
    font-weight: 500;
    margin: -16px 20px 0px 20px;
}
.panel-content {
    font-family: 'Roboto', sans-serif;
    font-size: var(--panel-text-size);
    line-height: 1.4;
    font-weight: 300;
    margin: 10px 20px 20px;
}

/* Spotlight */
.spotlight {
    position: fixed;
    width: 20vw;
    height: auto;
    aspect-ratio: 320/720;
    right: 20%;
    bottom: 10%;
    pointer-events: none;
    z-index: 0;
}
.spotlight .item {
    position: absolute;
    width: 100%;
    height: 100%;
    background-size: contain;
    background-repeat: no-repeat;
    background-position: bottom;
}
/* A white oval that stays behind the image while only the figure fades (the control screen turns it on; sizes in frame percentages: the frame is 320 x 720) */
.spotlight-oval {
    display: none;
    --oval-grow: 2;
    position: absolute;
    left: calc(var(--oval-grow) * -100% / 320);
    right: calc(var(--oval-grow) * -100% / 320);
    bottom: calc(var(--oval-grow) * -100% / 720);
    height: calc((120 + 2 * var(--oval-grow)) * 100% / 720);
    border-radius: 50%;
    background: #fff;
}
#spotlight-base{
    background-image: url(assets/images/spotlight/base.png);
    display: none;
}

/* Footer */
#copyright {
    font-size: 12px;
    width: 100%;
    text-align: center;
    position: fixed;
    bottom: 20px;
    color: gray;
}

/* Keyboard focus styles */
.panel-wrap:focus-visible {
    outline: 3px solid rgba(255, 255, 255, 0.8);
    outline-offset: 4px;
}
.panel-wrap:focus-visible .panel:not(.panel-clear) {
    box-shadow: 0 0 20px 8px rgba(255,255,255,0.6),
                10px 10px 10px rgba(0,0,0,0.3),
                -10px 10px 10px rgba(0,0,0,0.3),
                inset 5px 5px 10px rgba(0,0,0,0.2),
                inset -5px -5px 10px rgba(0,0,0,0.2);
}

/* Skip link for accessibility */
.skip-link {
    position: absolute;
    top: -48px;
    left: 0;
    background: #000;
    color: white;
    padding: 8px;
    text-decoration: none;
    z-index: 100;
    border: 2px solid white;
}

.skip-link:focus {
    top: 0;
}

.arrow-up, .arrow-down{
    text-align: left;

}
@media (hover: hover) and (pointer: fine) {
    /* Base panel-wrap: smooth transition, no glow */
    .panel-wrap:not(.is-clear) {
        transition: box-shadow 0.2s ease-in-out;
        box-shadow: 0 0 0 0 rgba(255,255,255,0); /* no glow initially */
    }

    /* Hover: subtle outer glow */
    .panel-wrap:not(.is-clear):hover {
        box-shadow: 0 0 12px 4px rgba(255,255,255,var(--panel-glow-a));
    }
}


/* Mobile responsive */
@media (max-width: 500px) {
    :root {
        --spot-pool: radial-gradient(circle at 50% 33vh, rgba(0,0,255,0.75) 0%, rgba(0,0,255,0) 60vw);
    }
    body::before {
        background-position: center 5vh, 0 0;
        background-size: 90%, 100%;
    }
}
@media (max-width: 500px) {
        /* Reset section padding and keep full viewport */

    .section {
    justify-content: center;
    align-items: flex-end;
    padding-left: 0; /* left offset */
    padding-bottom: 10vh; /* space for panels at bottom */

    }

    .panel-wrap {
        width: 90% !important;
        pointer-events: auto;
        cursor: pointer;
    }


    .spotlight {
        left: 50%;
        top: 10vh;
        transform: translate(-50%, 0);
        width: min(200px, 50vw);
        height: auto;
        aspect-ratio: 320/720;
        right: auto;
        bottom: auto;
        max-height: 40vh;
        max-width: calc(40vh * 320 / 720);
    }
    #copyright {
        font-size: 10px;
        bottom: 10px;
    }
}


/* A phone on its side (short and wide): the panel sits low, the logo and the spotlight image are sized by the screen's height, the small print is smaller */
@media (max-height: 500px) and (orientation: landscape) {
    body::before { background-position: 6% 8vh, 0 0; background-size: 42%, 100%; }
    .logo-layer { background-position: 6% 8vh; background-size: 42%; }
    .section { box-sizing: border-box; align-items: flex-end; padding-left: 6vw; padding-bottom: 10vh; }
    .spotlight { right: 10%; bottom: 6vh; }
    .panel-clear { padding-bottom: 0 !important; }
    .clear-text { font-size: var(--panel-text-size); }
    #copyright { font-size: 10px; bottom: 4px; }
    .static-link { top: 6px; right: 12px; }
}

/* rotation tweaks for mobile */
/* Ensure panels always fit nicely */
.panel-wrap {
    width: var(--panel-w); /* default: min 250px, max 400px, responsive in-between */
}

/* Spotlight size remains responsive - and never taller than the screen (the image is 320 x 720) */
.spotlight {
    width: min(clamp(150px, 20vw, 250px), 34vh);
    aspect-ratio: 320/720;
}


.static-link{
    position: fixed;
    top: 10px;
    right: 20px;
    z-index: 100;
}
.static-link a{
    color: white;
    text-decoration: none;
}

@media (max-width: 600px) {
.static-link a{
    font-size: 14px;
}
}

</style>
<?php
// Published by the control screen (/control/); absent = the page as it always was
$cssSpot = $draft['spot'] ?? ($homeConfig['spotlight_css'] ?? '');
$cssLook = $draft['look'] ?? ($homeConfig['look_css'] ?? '');
echo '<style id="ms-spotlight">' . str_replace('<', '', (string) $cssSpot) . "</style>\n";
echo '<style id="ms-look">' . str_replace('<', '', (string) $cssLook) . "</style>\n";
echo '<style id="ms-oval">' . str_replace('<', '', (string) ($homeConfig['oval_css'] ?? '')) . "</style>\n";
echo '<style id="ms-contact">' . str_replace('<', '', (string) ($homeConfig['contact_css'] ?? '')) . "</style>\n";
echo '<style id="ms-tagline">' . str_replace('<', '', (string) ($homeConfig['tagline_css'] ?? '')) . "</style>\n";
echo '<style id="ms-logo">' . str_replace('<', '', (string) ($homeConfig['logo_css'] ?? '')) . "</style>\n";
echo '<style id="ms-panels">' . str_replace('<', '', (string) ($homeConfig['panels_css'] ?? '')) . "</style>\n";
?>
</head>
<body>


<div class="logo-layer" id="logo-layer" aria-hidden="true"></div>

<?php if (!empty($homeSite['tagline_show']) && trim((string) ($homeSite['tagline_text'] ?? '')) !== ''): ?>
<p id="tagline"><?= $e($homeSite['tagline_text']) ?></p>
<?php endif; ?>

<a href="#<?= $e($homePanels[0]["id"]) ?>" class="skip-link">Skip to main content</a>

<?php if (!empty($homeSite['contact_show'])): ?>
<div class="static-link" id="contact-link">
    <a href="<?= $e($safeUrl($homeSite['contact_url'])) ?>"><?= $e($homeSite['contact_text']) ?></a>
</div>
<?php endif; ?>

<div class="spotlight" aria-hidden="true">
    <div class="spotlight-oval" id="spotlight-oval"></div>
    <div class="item" id="spotlight-base"></div>
    <div class="item" id="spotlight-item"></div>
</div>

<main role="main" aria-label="Portfolio sections">
<?php foreach ($homePanels as $p):
    $label = $p['label'] ?? '' ?: ($p['title'] ?? '');
    $text = nl2br($e($p['text'] ?? '')); ?>
<div class="section">
    <div class="panel-wrap<?= !empty($p['clear']) ? ' is-clear' : '' ?>" id="<?= $e($p['id']) ?>" data-spotlight="<?= $e($p['id']) ?>" tabindex="0" role="article" aria-label="<?= $e($label) ?>">
<?php if (!empty($p['clear'])): ?>
        <div class="panel panel-clear">
            <div class="arrow-up"></div>
            <div class="clear-text" style="padding:12px;"><?= $text ?></div>
            <div class="arrow-down"></div>
        </div>
<?php else: ?>
        <div class="panel">
            <div class="panel-top"></div>
            <div class="panel-header"><?= $e($p['title']) ?></div>
            <div class="panel-content">
                <?= $text ?>
            </div>
        </div>
<?php endif; ?>
    </div>
</div>
<?php endforeach; ?>
</main>

<footer id="copyright" role="contentinfo">
    <?= $e($homeSite['footer']) ?>
</footer>

<script>
// Sections spotlight setup
const spotlight = document.getElementById('spotlight-item');
const panelData = <?= json_encode(array_map(fn($p) => ['id' => $p['id'], 'image' => $p['image'] ? $p['image'] . '?v=' . (int) @filemtime(__DIR__ . '/' . $p['image']) : null, 'url' => ($safeUrl($p['url'] ?? '') ?: null)], $homePanels), JSON_UNESCAPED_SLASHES) ?>;
const PREVIEW = <?= $isPreview ? 'true' : 'false' ?>;
const sectionsObject = {};
panelData.forEach(p => {
    sectionsObject[p.id] = { isActive: false, spotlightImage: p.image, panel: document.getElementById(p.id), url: p.url };
});
const firstId = panelData[0].id;
function setSpotlightImage(src) { spotlight.style.backgroundImage = src ? `url(${src})` : 'none'; }
// Navigate - not in the control screen's preview, where a click must leave the page alone
function go(url) { if (!PREVIEW) window.location.href = url; }

// preload images
Object.values(sectionsObject).forEach(panel => {
    if (!panel.spotlightImage) return;
    const img = new Image();
    img.src = panel.spotlightImage;
});

function setActiveSection(name) {
    if (!sectionsObject[name]) return;
    Object.values(sectionsObject).forEach(sec => sec.isActive = false);
    sectionsObject[name].isActive = true;
}

function getActiveSection() {
    for (const [name, sectionObject] of Object.entries(sectionsObject)) {
        if (sectionObject.isActive) return { name, panel: sectionObject };
    }
    return null;
}

// Initialize with the first panel's image
setActiveSection(firstId);
setSpotlightImage(sectionsObject[firstId].spotlightImage);

// Expose for other scripts
window.getActiveSection = getActiveSection;
window.setActiveSection = setActiveSection;


// Make panels clickable and keyboard accessible
Object.values(sectionsObject).forEach(section => {
    if (section.url) {
        // Touch: navigate on touchend to avoid iOS bfcache synthetic-click failures
        let tapStartX = 0, tapStartY = 0;
        section.panel.addEventListener('touchstart', e => {
            tapStartX = e.touches[0].clientX;
            tapStartY = e.touches[0].clientY;
        }, { passive: true });
        section.panel.addEventListener('touchend', e => {
            const t = e.changedTouches[0];
            if (Math.abs(t.clientX - tapStartX) < 10 && Math.abs(t.clientY - tapStartY) < 10) {
                e.preventDefault(); // suppress synthetic click so we don't navigate twice
                go(section.url);
            }
        });

        // Click handler (mouse / non-touch)
        section.panel.addEventListener('click', () => {
            if (dragDistance > 5) return; // the mouse was dragging, not clicking
            go(section.url);
        });

        // Enter/Space key handler
        section.panel.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                go(section.url);
            }
        });
    }
});

// Reset drag/velocity state when page is restored from bfcache (iOS back navigation)
window.addEventListener('pageshow', e => {
    if (e.persisted) {
        dragging = false;
        velocity = 0;
        y = Math.round(y / vh) * vh;
        positionSections();
    }
});


</script>

<script>
/* --------------------------------------------------------- BASIC SETUP --------------------------------------------------------- */
const sections = Array.from(document.querySelectorAll('.section'));
const count = sections.length;
// How far apart the panels are: 3/4 of the screen's height - a short, wide screen (a phone on its side) gets more, so the next panel does not show
const shortLandscape = () => window.matchMedia('(max-height: 500px) and (orientation: landscape)').matches;
const step = () => window.innerHeight * (shortLandscape() ? 0.92 : 0.75);
let vh = step();
let y = -(<?= (int) $focusIndex ?>) * vh; // the control screen's preview opens at the panel being edited
let dragging = false;
let lastY = 0;
let lastTime = 0;
let velocity = 0;
let dragDistance = 0;
const friction = 0.92;

// Rotating the phone changes the step: keep the same place between the panels, drop any momentum, and re-measure once the browser has settled
function refit() {
    const place = -y / vh;
    vh = step();
    y = -place * vh;
    velocity = 0;
    dragging = false;
    positionSections();
    window.scrollTo(0, 0);
}
window.addEventListener("resize", refit);
window.addEventListener("orientationchange", () => { refit(); setTimeout(refit, 250); });

let lastOpacity = 1;

function getCurrentSectionIndex() {
    let index = Math.round(-y / vh);
    index = ((index % count) + count) % count;
    return index;
}


function updateSpotlight() {
    // Compute which section we're closest to
    let rawIndex = -y / vh;
    let nearest = Math.round(rawIndex);

    // Distance from the center of the nearest section, scaled 0 -> 1
    let dist = Math.abs(rawIndex - nearest) * 2; // multiply by 2 to reach 1 at halfway
    if (dist > 1) dist = 1;

    // Simple plateau-fade opacity
    let opacity;
    if (dist < 0.25) {
        opacity = 1; // full opacity near center
    } else {
        opacity = 1 - (dist - 0.25) / 0.75; // fade to 0 at dist=1
    }

    spotlight.style.opacity = opacity;

    // Switch image at the nearest section center
    let idx = ((nearest % count) + count) % count;
    let sectionId = sections[idx].querySelector('.panel-wrap').dataset.spotlight;

    if (!sectionsObject[sectionId].isActive) {
        setSpotlightImage(sectionsObject[sectionId].spotlightImage);
        setActiveSection(sectionId);
    }
}


/* --------------------------------------------------------- POSITIONING --------------------------------------------------------- */
function positionSections() {
    const total = count * vh;
    const isMobile = window.innerWidth <= 500;

    sections.forEach((s, i) => {
        let pos = i * vh + y;
        while (pos < -vh) pos += total;
        while (pos > total - vh) pos -= total;
        s.style.top = pos + "px";

        // On mobile: fade panels at viewport edges
        if (isMobile) {
            const viewportHeight = window.innerHeight;
            const fadeZone = 100; // Fade distance in pixels

            // The section's top position is 'pos'
            // The section is 100vh tall, panel is at bottom (flex-end + padding-bottom: 10vh)
            // So the actual visible panel region is roughly from pos+70vh to pos+90vh
            // We want to fade based on where this panel region intersects viewport edges

            const panelTop = pos + (0.7 * viewportHeight);    // Top of panel area
            const panelBottom = pos + (0.9 * viewportHeight); // Bottom of panel area

            let opacity = 1;

            // Fade when panel top goes above viewport top
            if (panelTop < fadeZone) {
                opacity = Math.min(opacity, Math.max(0, panelTop / fadeZone));
            }

            // Fade when panel bottom goes below viewport bottom
            if (panelBottom > viewportHeight - fadeZone) {
                const distFromBottom = viewportHeight - panelBottom;
                opacity = Math.min(opacity, Math.max(0, 1 + (distFromBottom / fadeZone)));
            }

            s.style.opacity = opacity;
        } else {
            s.style.opacity = 1;
        }
    });
    updateSpotlight();
}

/* --------------------------------------------------------- DRAGGING / TOUCH --------------------------------------------------------- */
function startDrag(e) {
    dragging = true;
    velocity = 0;
    dragDistance = 0;
    lastY = e.touches ? e.touches[0].clientY : e.clientY;
    lastTime = performance.now();
}
function moveDrag(e) {
    if (!dragging) return;
    if (e.touches && e.touches.length > 1) { dragging = false; return; } // pinch-zoom, not a scroll
    const nowY = e.touches ? e.touches[0].clientY : e.clientY;
    const dy = nowY - lastY;
    dragDistance += Math.abs(dy);
    y += dy;
    const now = performance.now();
    const dt = now - lastTime || 16;
    velocity = dy / dt * 16.66;
    lastY = nowY;
    lastTime = now;
    positionSections();
}
function endDrag() { dragging = false; }

/* --------------------------------------------------------- WHEEL / TRACKPAD --------------------------------------------------------- */
document.addEventListener("wheel", e => {
    e.preventDefault();
    const delta = -e.deltaY;
    y += delta;
    velocity = delta;
    positionSections();
}, { passive:false });

/* --------------------------------------------------------- KEYBOARD NAVIGATION --------------------------------------------------------- */
document.addEventListener("keydown", e => {
    // Arrow keys for navigation
    if (e.key === 'ArrowUp' || e.key === 'ArrowDown') {
        e.preventDefault();

        const direction = e.key === 'ArrowUp' ? 1 : -1;
        const targetY = Math.round(y / vh) * vh + (direction * vh);

        // Smooth scroll to target
        const startY = y;
        const distance = targetY - startY;
        const duration = 400; // ms
        const startTime = performance.now();

        function smoothScroll(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);

            // Ease out cubic
            const eased = 1 - Math.pow(1 - progress, 3);

            y = startY + (distance * eased);
            velocity = 0; // Stop momentum during keyboard nav
            positionSections();

            if (progress < 1) {
                requestAnimationFrame(smoothScroll);
            } else {
                // Focus the current panel after navigation
                const currentIndex = getCurrentSectionIndex();
                const currentSection = sections[currentIndex];
                const panelWrap = currentSection.querySelector('.panel-wrap');
                if (panelWrap) {
                    panelWrap.focus({ preventScroll: true });
                }
            }
        }

        requestAnimationFrame(smoothScroll);
    }
});

/* --------------------------------------------------------- MOMENTUM LOOP --------------------------------------------------------- */
function animate() {
    if (!dragging) {
        if (Math.abs(velocity) > 0.01) {
            y += velocity;
            velocity *= friction;
            positionSections();
        }
    }
    requestAnimationFrame(animate);
}
requestAnimationFrame(animate);

/* --------------------------------------------------------- EVENT HOOKS --------------------------------------------------------- */
document.addEventListener("mousedown", startDrag);
document.addEventListener("mousemove", moveDrag);
document.addEventListener("mouseup", endDrag);
document.addEventListener("mouseleave", endDrag);
document.addEventListener("touchstart", startDrag, { passive:false });
document.addEventListener("touchmove", moveDrag, { passive:false });
document.addEventListener("touchend", endDrag);
document.addEventListener("touchcancel", endDrag);

<?php if ($isPreview): ?>
/* Live preview inside the control screen (/control/, same site): it posts the CSS, the page shows it at once */
window.addEventListener('message', e => {
    if (e.origin !== location.origin || !e.data || e.data.type !== 'ms-css') return;
    for (const [id, css] of [['ms-spotlight', e.data.spot], ['ms-look', e.data.look]]) {
        if (typeof css === 'string') document.getElementById(id).textContent = css;
    }
});
if (window.parent !== window) window.parent.postMessage({ type: 'ms-ready' }, location.origin);
<?php endif; ?>

/* Initial paint */
positionSections();



</script>

</body>
</html>
