<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>monospace</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
<style>
/* Base styles */
html, body {
    margin: 0;
    font-family: monospace;
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
                radial-gradient(circle at 50% 33vh, rgba(0,0,255,0.75) 0%, rgba(0,0,255,0) 33vw),
                #000;
    background-size: 70%, 100%, 100%;
}

.section {
    position: absolute;
    width: 100vw;
    height: 100vh;
    display: flex;
    justify-content: flex-start; /* moved left of center */
    align-items: center;
    padding-left: 10vw; /* left offset */
    flex: 2;
}

/* panel panels */
.panel-wrap {
    width: 30vw;
    backdrop-filter: blur(5px);
    border-radius: 16px;
}
.panel {
    box-shadow: 10px 10px 10px rgba(0,0,0,0.3),
                -10px 10px 10px rgba(0,0,0,0.3),
                inset 5px 5px 10px rgba(0,0,0,0.2),
                inset -5px -5px 10px rgba(0,0,0,0.2);
    background: linear-gradient(to bottom, rgba(255,255,255,0.3) 0px, rgba(255,255,255,0) 40px, rgba(255,255,255,0) 90%),
                linear-gradient(to top, rgba(255,255,255,0.3) 0px, rgba(255,255,255,0) 20px, rgba(255,255,255,0.1) 40%, rgba(255,255,255,0) 100%),
                rgba(0,0,0,0);
    border-radius: 16px;
    border: solid 1px rgba(255,255,255,0.3);
    color: white;
    cursor: pointer;
}
.panel-clear{
    box-shadow: none;
    background: none;
    border: none;
    cursor: default;
}
.panel-top {
    height: 20px;
    margin: 4px 4px 0 4px;
    background: linear-gradient(rgba(255,255,255,0.4) 0px, rgba(255,255,255,0) 20px);
    border-radius: 10px 10px 0 0;
}
.panel-header {
    font-family: 'Oswald', sans-serif;
    font-size: clamp(1.8rem, 2.2vw, 2.2rem);
    font-weight: 500;
    margin: -16px 20px 0px 20px;
}
.panel-content {
    font-family: 'Roboto', sans-serif;
    font-size: clamp(1.2rem, 1.5vw, 1.5rem);
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
#spotlight-base{ background-image: url(assets/images/spotlight/base.png) }

/* Footer */
#copyright {
    font-size: 12px;
    width: 100%;
    text-align: center;
    position: fixed;
    bottom: 20px;
    color: gray;
}

/* Mobile responsive */
@media (max-width: 500px) {
    body::before {
        background: url('/assets/images/logo_xl_transp.png') center 5vh no-repeat,
                    radial-gradient(circle at 50% 33vh, rgba(0,0,255,0.75) 0%, rgba(0,0,255,0) 60vw),
                    #000;
        background-size: 90%, 100%, 100%;
    }
    .section-header { font-size: 20px; }
    .section-content { font-size: 14px; }
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
    .section-wrap {
        width: calc(100% - 40px);
        min-width: auto;
        left: 20px;
        top: unset;
        bottom: 5%;
    }
    #copyright {
        font-size: 10px;
        bottom: 10px;
    }
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
            <div class="_panel-header">Welcome!</div>
            <div class="_panel-content">
                <br><br>Scroll or swipe up and down to navigate this website. Each section shows a different aspect of my work. Click a panel to explore further.
            </div>
        </div>
    </div>
</div>
<div class="section">
    <div class="panel-wrap" id="art" data-spotlight="art">
        <div class="panel">
            <div class="panel-top"></div>
            <div class="panel-header">monospace ART</div>
            <div class="panel-content">
                I'm a painter and illustrator working in gouache, casein, acrylic, and pen and ink. I explore mood, light, and structure through landscapes, cityscapes, and nature studies. My works are available for purchase, and I welcome commissions.
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
                A visual and personal exploration of meals I've cooked, enjoyed, and photographed. This panel is a documentation of flavors, presentations, and culinary moments, shared to inspire and celebrate the experience of food.
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
                With decades of hands-on experience, I create interactive experiences that are both engaging and memorable. From web-based mini-games to immersive multimedia projects, my portfolio showcases a wide range of creative coding and design skills.
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
    <div class="panel-wrap" id="digits" data-spotlight="digits">
        <div class="panel">
            <div class="panel-top"></div>
            <div class="panel-header">DIGITAL SEX LIFE</div>
            <div class="panel-content">
                An early interactive artwork from 1992 where numbers become digital agents, moving, bonding, aging, and reproducing. Newly reimagined and rewritten with the aid of AI.
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
                I restore and colorize vintage black-and-white photographs, paying close attention to historical accuracy and visual detail. Each piece is carefully crafted to bring old images vividly back to life.
            </div>
        </div>
    </div>
</div>
<div class="section">
    <div class="panel-wrap" id="music" data-spotlight="music">
        <div class="panel">
            <div class="panel-top"></div>
            <div class="panel-header">monospace MUSIC</div>
            <div class="panel-content">
                I play drums and guitar and write original music. Here you can explore my recordings, live videos, and songs — all gathered in one place to showcase my musical work.
            </div>
        </div>
    </div>
</div>
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
    art: { isActive: false, spotlightImage: 'assets/images/spotlight/easel.png', panel: document.getElementById('art'), url: 'art' },
    food: { isActive: false, spotlightImage: 'assets/images/spotlight/tocque.png', panel: document.getElementById('food'), url: 'food' },
    interactive: { isActive: false, spotlightImage: 'assets/images/spotlight/controller.png', panel: document.getElementById('interactive'), url: 'https://monospace.com/hens/portfolio/wordpress/portfolio/publishers-clearing-house/monthly-facebook-mini-games/' },
    console: { isActive: false, spotlightImage: 'assets/images/spotlight/console.png', panel: document.getElementById('console'), url: 'console' },
    digits: { isActive: false, spotlightImage: 'assets/images/spotlight/digits.png', panel: document.getElementById('digits'), url: 'https://www.digitalsexlife.com' },
    color: { isActive: false, spotlightImage: 'assets/images/spotlight/paintbucket.png', panel: document.getElementById('color'), url: 'color' },
    music: { isActive: false, spotlightImage: 'assets/images/spotlight/guitar.png', panel: document.getElementById('music'), url: 'music' },
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
</script>

<script>
/* --------------------------------------------------------- BASIC SETUP --------------------------------------------------------- */
const sections = Array.from(document.querySelectorAll('.section'));
const count = sections.length;
let vh = window.innerHeight;
let y = 0;
let dragging = false;
let lastY = 0;
let lastTime = 0;
let velocity = 0;
const friction = 0.92;

window.addEventListener("resize", () => { vh = window.innerHeight; positionSections(); });

let lastOpacity = 1;

function getCurrentSectionIndex() {
    let index = Math.round(-y / vh);
    index = ((index % count) + count) % count;
    return index;
}

function updateSpotlight() {
    let rawIndex = -y / vh;
    let nearest = Math.round(rawIndex);
    let dist = Math.abs(rawIndex - nearest);
    let opacity = 1 - dist * 2;
    opacity = Math.max(0, Math.min(1, opacity));
    spotlight.style.opacity = opacity;

    if (opacity > lastOpacity && lastOpacity < 0.05) {
        let idx = getCurrentSectionIndex();
        let sectionId = sections[idx].querySelector('.panel-wrap').dataset.spotlight;
        spotlight.style.backgroundImage = `url(${sectionsObject[sectionId].spotlightImage})`;
        setActiveSection(sectionId);
    }
    lastOpacity = opacity;
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
</script>

</body>
</html>
