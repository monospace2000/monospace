<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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
<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

<style>
/* Base styles */
html, body {
    margin: 0;
    font-family: monospace;
    font-size: 1.2em;
    color: white;
    height: 100vh;
    overflow: hidden;
    background-color: #000;
}
body::before {
    content: '';
    position: fixed;
    inset: 0;
    z-index: 0;
    background: url('/assets/images/logo_xl_transp.png') 10% 10vh no-repeat,
                radial-gradient(ellipse 60% 40% at 20% 0vh, rgba(0,0,255,0.75) 0%, rgba(0,0,255,0) 55vw),
                #000;
    background-size: 55%, 100%, 100%;
}

.section {
    position: absolute;
    width: 100vw;
    height: 100vh;
    display: flex;
    justify-content: flex-start; /* moved left of center */
    align-items: center;
    padding-left: 10vw; /* left offset */
}

/* panel panels */
.panel-wrap {
    width: 30vw;
    border-radius: 16px;
}
.panel {
    box-shadow: 10px 10px 10px rgba(0,0,0,0.3),
                -10px 10px 10px rgba(0,0,0,0.3),
                inset 5px 5px 10px rgba(0,0,0,0.2),
                inset -5px -5px 10px rgba(0,0,0,0.2);
    background: linear-gradient(to bottom, rgba(255,255,255,0.3) 0px, rgba(255,255,255,0) 40px, rgba(255,255,255,0) 90%),
                linear-gradient(to top, rgba(255,255,255,0.3) 0px, rgba(255,255,255,0) 20px, rgba(255,255,255,0.1) 40%, rgba(255,255,255,0) 100%),
                rgba(0,0,0,0.3);
    border-radius: 16px;
    border: solid 1px rgba(255,255,255,0.3);
    color: white;
    cursor: pointer;
    backdrop-filter: blur(5px);
}
.panel-clear{
    box-shadow: none;
    background: none;
    border: none;
    cursor: default;
    backdrop-filter: none;
}
.panel-top {
    height: 20px;
    margin: 4px 4px 0 4px;
    background: linear-gradient(rgba(255,255,255,0.5) 0px, rgba(255,255,255,0) 20px);
    border-radius: 10px 10px 0 0;
}
.panel-header {
    font-family: 'Oswald', sans-serif;
    font-size: clamp(20px, 24px, 28px);
    font-weight: 500;
    margin: -16px 20px 0px 20px;
}
.panel-content {
    font-family: 'Roboto', sans-serif;
    font-size: clamp(16px, 18px, 20px);
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

.arrow-up, .arrow-down{
    text-align: left;

}
@media (hover: hover) and (pointer: fine) {
    /* Base panel-wrap: smooth transition, no glow */
    .panel-wrap:not(#welcome) {
        transition: box-shadow 0.2s ease-in-out;
        box-shadow: 0 0 0 0 rgba(255,255,255,0); /* no glow initially */
    }

    /* Hover: subtle outer glow */
    .panel-wrap:not(#welcome):hover {
        box-shadow: 0 0 12px 4px rgba(255,255,255,0.5);
    }
}


/* Mobile responsive */
@media (max-width: 500px) {
    body::before {
        background: url('/assets/images/logo_xl_transp.png') center 5vh no-repeat,
                    radial-gradient(circle at 50% 33vh, rgba(0,0,255,0.75) 0%, rgba(0,0,255,0) 60vw),
                    #000;
        background-size: 90%, 100%, 100%;
    }
}
@media (max-width: 500px) {
        /* Reset section padding and keep full viewport */

    .section {
    justify-content: center;
    align-items: bottom;
    padding-left: 0; /* left offset */

    }

    .panel-wrap {
        width: 90%;
    }


    .spotlight {
        left: 50%;
        top: 35%;
        transform: translate(-50%, -40%);
        width: min(200px, 50vw);
        height: auto;
        aspect-ratio: 320/720;
        right: auto;
        bottom: auto;
        max-height: calc(100vh - 35vh - 30%);
        max-width: calc((100vh - 35vh - 30%) * 320 / 720);
    }
    #copyright {
        font-size: 10px;
        bottom: 10px;
    }
}


/* rotation tweaks for mobile */
/* Ensure panels always fit nicely */
.panel-wrap {
    width: clamp(250px, 30vw, 400px); /* min 250px, max 400px, responsive in-between */
}

/* Adjust panel content for readability on all sizes */
.panel-header {
    _font-size: clamp(16px, 24px, 28px);
}
.panel-content {
    _font-size: clamp(12px, 16px, 18px);
}

/* Spotlight size remains responsive */
.spotlight {
    width: clamp(150px, 20vw, 250px);
    aspect-ratio: 320/720;
}




</style>
</head>
<body>

<div class="spotlight">
    <div class="item" id="spotlight-base"></div>
    <div class="item" id="spotlight-item"></div>
</div>

<div class="section">
    <div class="panel-wrap" id="welcome" data-spotlight="welcome">
        <div class="panel panel-clear">
            <div class="_panel-top"></div>
            <div class="_panel-header"><!--header--></div>
            <div class="arrow-up"></div>
            <div class="_panel-content">
                Welcome!
                <br><br>Scroll or swipe up and down to navigate this page. Each section shows a different aspect of my work. Click a panel to explore further.
            </div>
            <div class="arrow-down"></div>
        </div>
    </div>
</div>
<div class="section">
    <div class="panel-wrap" id="art" data-spotlight="art">
        <div class="panel">
            <div class="panel-top"></div>
            <div class="panel-header">monospace ART</div>
            <div class="panel-content">
                Painting and sketches exploring mood, light, and structure through landscapes, cityscapes, and nature studies. All works are available for purchase. Commissions welcome.
            </div>
        </div>
    </div>
</div>
<div class="section">
    <div class="panel-wrap" id="sightings" data-spotlight="sightings">
        <div class="panel">
            <div class="panel-top"></div>
            <div class="panel-header">Sightings of the Strangely Familiar</div>
            <div class="panel-content">
                A series of original digital sketches made with Procreate, captioned by Mindy.
            </div>
        </div>
    </div>
</div>
<div class="section">
    <div class="panel-wrap" id="food" data-spotlight="food">
        <div class="panel">
            <div class="panel-top"></div>
            <div class="panel-header">monospace FOOD</div>
            <div class="panel-content">
                A visual exploration of meals I've cooked, enjoyed, and photographed. A documentation of flavors, presentations, and culinary moments, shared to inspire and celebrate the experience of food.
            </div>
        </div>
    </div>
</div>
<div class="section">
    <div class="panel-wrap" id="interactive" data-spotlight="interactive">
        <div class="panel">
            <div class="panel-top"></div>
            <div class="panel-header">monospace INTERACTIVE</div>
            <div class="panel-content">
                I have decades of hands-on experience designning and coding interactive experiences that are both engaging and memorable, from web-based mini-games to immersive multimedia projects.
            </div>
        </div>
    </div>
</div>
<div class="section">
    <div class="panel-wrap" id="console" data-spotlight="console">
        <div class="panel">
            <div class="panel-top"></div>
            <div class="panel-header">THE MONOSPACE CONSOLE</div>
            <div class="panel-content">
                The original monospace console, which started this website. A Shockwave art project from 1998, revived in modern Javascript. A chatty command-line console you can click, type, and poke at.
            </div>
        </div>
    </div>
</div>
<div class="section">
    <div class="panel-wrap" id="wayback" data-spotlight="wayback">
        <div class="panel">
            <div class="panel-top"></div>
            <div class="panel-header">2001 TIME CAPSULE</div>
            <div class="panel-content">
                This website has been around since 1998. It's changed many times since then, but it was always state of the art. Here is a snapshot of what it looked like in July of 2001, as found on the Wayback Machine.
            </div>
        </div>
    </div>
</div>
<div class="section">
    <div class="panel-wrap" id="digits" data-spotlight="digits">
        <div class="panel">
            <div class="panel-top"></div>
            <div class="panel-header">DIGITAL SEX LIFE</div>
            <div class="panel-content">
                An early interactive artwork from 1992 where numbers become digital agents, moving, bonding, aging, and reproducing. Newly reimagined and rewritten with a little aid of an LLM coding assistant.
            </div>
        </div>
    </div>
</div>
<div class="section">
    <div class="panel-wrap" id="color" data-spotlight="color">
        <div class="panel">
            <div class="panel-top"></div>
            <div class="panel-header">monospace COLOR</div>
            <div class="panel-content">
                I like to restore and colorize vintage black-and-white photographs by hand in Photoshop, paying close attention to historical accuracy and visual detail. Your "AI" can't do that.
            </div>
        </div>
    </div>
</div>
<!-- <div class="section">
    <div class="panel-wrap" id="music" data-spotlight="music">
        <div class="panel">
            <div class="panel-top"></div>
            <div class="panel-header">monospace MUSIC</div>
            <div class="panel-content">
                I play drums and guitar, and I write original music. Here you can find some of my recordings, live videos, and songs. (SOON)
            </div>
        </div>
    </div>
</div> -->
<div class="section">
    <div class="panel-wrap" id="portfolio" data-spotlight="portfolio">
        <div class="panel">
            <div class="panel-top"></div>
            <div class="panel-header">PORTFOLIO</div>
            <div class="panel-content">
                A curated selection of my professional work. Browse my resume, portfolio, and key projects across art, interactive media, and more.
            </div>
        </div>
    </div>
</div>

<div id="copyright">
    Copyright &copy; 1998&mdash;2025 monospace. You are visitor number one.
</div>

<script>
// Sections spotlight setup
const spotlight = document.getElementById('spotlight-item');
const sectionsObject = {
    welcome: { isActive: false, spotlightImage: 'assets/images/spotlight/hand.png', panel: document.getElementById('welcome'), url: null },
    art: { isActive: false, spotlightImage: 'assets/images/spotlight/easel.png', panel: document.getElementById('art'), url: 'https://art.monospace.com' },
    wayback: { isActive: false, spotlightImage: 'assets/images/spotlight/cabinet.png', panel: document.getElementById('wayback'), url: 'wayback' },
    sightings: { isActive: false, spotlightImage: 'assets/images/spotlight/sightings.png', panel: document.getElementById('sightings'), url: 'https://sightings.monospace.com' },
    food: { isActive: false, spotlightImage: 'assets/images/spotlight/tocque.png', panel: document.getElementById('food'), url: 'https://food.monospace.com' },
    interactive: { isActive: false, spotlightImage: 'assets/images/spotlight/controller.png', panel: document.getElementById('interactive'), url: 'https://monospace.com/hens/portfolio/wordpress/portfolio/publishers-clearing-house/monthly-facebook-mini-games/' },
    console: { isActive: false, spotlightImage: 'assets/images/spotlight/console.png', panel: document.getElementById('console'), url: 'console' },
    digits: { isActive: false, spotlightImage: 'assets/images/spotlight/digits.png', panel: document.getElementById('digits'), url: 'https://www.digitalsexlife.com' },
    color: { isActive: false, spotlightImage: 'assets/images/spotlight/color.png', panel: document.getElementById('color'), url: 'https://color.monospace.com' },
    music: { isActive: false, spotlightImage: 'assets/images/spotlight/drum.png', panel: document.getElementById('music'), url: 'https://music.monospace.com' },
    portfolio: { isActive: false, spotlightImage: 'assets/images/spotlight/briefcase.png', panel: document.getElementById('portfolio'), url: 'hens/portfolio' }
};

// preload images
Object.values(sectionsObject).forEach(panel => {
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

function showOnlyActivePanel() {
    Object.values(sectionsObject).forEach(section => {
        section.panel.style.display = section.isActive ? 'block' : 'none';
    });
}

// Initialize with hand
setActiveSection('welcome');
spotlight.style.backgroundImage = `url(${sectionsObject['welcome'].spotlightImage})`;

// Expose for other scripts
window.getActiveSection = getActiveSection;
window.setActiveSection = setActiveSection;


// Make panels clickable based on their URL in sectionsObject
Object.values(sectionsObject).forEach(section => {
    if (section.url) {
        section.panel.addEventListener('click', () => {
            window.location.href = section.url;
        });
    }
});


</script>

<script>
/* --------------------------------------------------------- BASIC SETUP --------------------------------------------------------- */
const sections = Array.from(document.querySelectorAll('.section'));
const count = sections.length;
let vh = window.innerHeight*0.75;
let y = 0;
let dragging = false;
let lastY = 0;
let lastTime = 0;
let velocity = 0;
const friction = 0.92;

window.addEventListener("resize", () => { vh = window.innerHeight*0.75; positionSections(); });

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
        spotlight.style.backgroundImage = `url(${sectionsObject[sectionId].spotlightImage})`;
        setActiveSection(sectionId);
    }
}


/* --------------------------------------------------------- POSITIONING --------------------------------------------------------- */
function positionSections() {
    const total = count * vh;
    sections.forEach((s, i) => {
        let pos = i * vh + y;
        while (pos < -vh) pos += total;
        while (pos > total - vh) pos -= total;
        s.style.top = pos + "px";
    });
    updateSpotlight();
}

/* --------------------------------------------------------- DRAGGING / TOUCH --------------------------------------------------------- */
function startDrag(e) {
    dragging = true;
    velocity = 0;
    lastY = e.touches ? e.touches[0].clientY : e.clientY;
    lastTime = performance.now();
}
function moveDrag(e) {
    if (!dragging) return;
    const nowY = e.touches ? e.touches[0].clientY : e.clientY;
    const dy = nowY - lastY;
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

/* Initial paint */
positionSections();



// Handle window resize gracefully
function handleResize() {
    vh = window.innerHeight; // update viewport height
    positionSections();       // reposition sections
}
let resizeTimeout;
window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(handleResize, 100);
});


</script>

</body>
</html>
