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
    background-repeat: no-repeat;
}
.container {
    position: relative;
    width: 100%;
    height: 100%;
    z-index: 1;
}
.section-wrap {
    width: 30vw;
    min-width: 250px;
    backdrop-filter: blur(5px);
    position: fixed;
    left: 140px;
    top: 30vh;
    transform: translateY(0);
    opacity: 0;
    z-index: 1;
    transition: transform 0.8s ease, opacity 0.8s ease;
}
.section-wrap.active {
    transform: translateY(0);
    opacity: 1;
}
.section {
    box-shadow: 10px 10px 10px rgba(0,0,0,0.3),
        -10px 10px 10px rgba(0,0,0,0.3),
        inset 5px 5px 10px rgba(0,0,0,0.2),
        inset -5px -5px 10px rgba(0,0,0,0.2);
    position: relative;
    background: linear-gradient(to bottom, rgba(255,255,255,0.3) 0px, rgba(255,255,255,0) 40px, rgba(255,255,255,0) 90%),
                linear-gradient(to top, rgba(255,255,255,0.3) 0px, rgba(255,255,255,0) 20px, rgba(255,255,255,0.1) 40%, rgba(255,255,255,0) 100%),
                rgba(0,0,0,0);
    border-radius: 16px;
    border: solid 1px rgba(255,255,255,0.3);
    color: white;
    cursor: pointer;
}
.section-top {
    height: 20px;
    margin: 4px 4px 0 4px;
    background: linear-gradient(rgba(255,255,255,0.4) 0px, rgba(255,255,255,0) 20px);
    border-radius: 10px 10px 0 0;
}
.section-header {
    font-family: 'Oswald';
    font-size: 26px;
    font-weight: 500;
    margin: -16px 20px 0px 20px;
}
.section-content {
    font-family: 'Roboto';
    font-size: 19px;
    line-height: 1.4;
    font-weight: 300;
    margin: 10px 20px 20px;
}
.spotlight {
    position: fixed;
    width: 320px;
    height: 720px;
    right: 160px;
    bottom: 80px;
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
    transition: background-image 0.5s ease-in-out;
}
#copyright {
    font-size: 12px;
    width: 100%;
    text-align: center;
    position: fixed;
    bottom: 20px;
    color: gray;
}
@media (max-width: 768px) {
    body::before {
        background: url('/assets/images/logo_xl_transp.png') center 5vh no-repeat,
            radial-gradient(circle at 50% 33vh, rgba(0,0,255,0.75) 0%, rgba(0,0,255,0) 60vw),
            #000;
        background-size: 90%, 100%, 100%;
        background-repeat: no-repeat;
    }
    .section-header {
    font-size: 20px;
}
.section-content {
    font-size: 14px;
}
    .spotlight {
        position: fixed;
        left: 50%;
        top: 10vh;
        transform: translateX(-50%);
        width: 200px;
        height: 450px;
        right: auto;
        bottom: auto;
    }
.section-wrap {
    width: calc(100% - 40px);
    min-width: auto;
    left: 20px;
    top: unset;
    bottom: 5%; /* changed from 10% to 5% */
    transform: translateY(0);
}

    #copyright {
        position: fixed;
        font-size: 10px;
        bottom: 10px;
    }
}
</style>
</head>
<body>

<div class="spotlight">
    <div class="item" id="spotlight-current"></div>
    <div class="item" id="spotlight-base"></div>
    <div class="item" id="spotlight-next"></div>
</div>


<div class="container">

    <!-- Panels -->
    <div class="section-wrap">
        <div class="section" id="welcome">
            <div class="section-top"></div>
            <div class="section-header">Welcome</div>
            <div class="section-content">
                Scroll or swipe to navigate through the panels. Each section shows a different aspect of my work. Enjoy.
            </div>
        </div>
    </div>

    <div class="section-wrap">
        <div class="section" id="art" onclick="document.location.href='art'">
            <div class="section-top"></div>
            <div class="section-header">monospace | art</div>
            <div class="section-content">
                I'm a painter and illustrator working in gouache, casein, acrylic, and pen and ink. I explore mood, light, and structure through landscapes, cityscapes, and nature studies. My works are available for purchase, and I welcome commissions.
            </div>
        </div>
    </div>

    <div class="section-wrap">
        <div class="section" id="interactive" onclick="document.location.href='https://monospace.com/hens/portfolio/wordpress/portfolio/publishers-clearing-house/monthly-facebook-mini-games/'">
            <div class="section-top"></div>
            <div class="section-header">monospace | interactive</div>
            <div class="section-content">
                With decades of hands-on experience, I create interactive experiences that are both engaging and memorable. From web-based mini-games to immersive multimedia projects, my portfolio showcases a wide range of creative coding and design skills.
            </div>
        </div>
    </div>

    <div class="section-wrap">
        <div class="section" id="color" onclick="document.location.href='color'">
            <div class="section-top"></div>
            <div class="section-header">monospace | color</div>
            <div class="section-content">
                I specialize in restoring and colorizing vintage black-and-white photographs, paying close attention to historical accuracy and visual detail. Each piece is carefully crafted to bring old images vividly back to life.
            </div>
        </div>
    </div>

    <div class="section-wrap">
        <div class="section" id="music" onclick="document.location.href='music'">
            <div class="section-top"></div>
            <div class="section-header">monospace | music</div>
            <div class="section-content">
               I play drums and guitar and write original music. Here you can explore my recordings, live videos, and songs — all gathered in one place to showcase my musical work.
            </div>
        </div>
    </div>

    <div class="section-wrap">
        <div class="section" id="food" onclick="document.location.href='food'">
            <div class="section-top"></div>
            <div class="section-header">monospace | food</div>
            <div class="section-content">
                A visual and personal exploration of meals I've cooked, enjoyed, and photographed. This section is a documentation of flavors, presentations, and culinary moments, shared to inspire and celebrate the experience of food.
            </div>
        </div>
    </div>

    <div class="section-wrap">
        <div class="section" id="portfolio" onclick="document.location.href='hens/portfolio'">
            <div class="section-top"></div>
            <div class="section-header">monospace | portfolio</div>
            <div class="section-content">
                A curated selection of my professional work. Browse my resume, portfolio, and key projects across art, interactive media, and more.
            </div>
        </div>
    </div>

</div>

<div id="copyright">
    Copyright &copy; 2025 monospace. All rights reserved.
</div>

<script>
const spotlightImages = {
    base: 'assets/images/spotlight/base.png',
    welcome: 'assets/images/spotlight/hand.png',
    art: 'assets/images/spotlight/easel.png',
    interactive: 'assets/images/spotlight/controller.png',
    color: 'assets/images/spotlight/paintbucket.png',
    music: 'assets/images/spotlight/guitar.png',
    food: 'assets/images/spotlight/tocque.png',
    portfolio: 'assets/images/spotlight/briefcase.png'
};

// preload images
Object.values(spotlightImages).forEach(src => { const img = new Image(); img.src = src; });

const panels = Array.from(document.querySelectorAll('.section-wrap'));
const spotlightCurrent = document.getElementById('spotlight-current');
const spotlightBase = document.getElementById('spotlight-base');
const spotlightNext = document.getElementById('spotlight-next');

let currentIndex = 0;
let isAnimating = false;
const defaultTop = window.innerWidth <= 768 ? window.innerHeight * 0.9 : window.innerHeight * 0.3;

// Initialize panels
panels.forEach((panel, i) => {
    panel.style.position = 'fixed';
    panel.style.left = window.innerWidth <= 768 ? '20px' : '140px';
    if (window.innerWidth <= 768) {
        panel.style.bottom = i === 0 ? '5%' : '-100%'; // changed from 10%
        panel.style.top = 'auto';
    } else {
        panel.style.top = i === 0 ? `${defaultTop}px` : '100%';
        panel.style.bottom = 'auto';
    }
    panel.style.opacity = i === 0 ? '1' : '0';
    panel.style.zIndex = i === 0 ? '2' : '1';
});

// Initialize spotlight layers
spotlightCurrent.style.backgroundImage = `url(${spotlightImages.welcome})`;
spotlightCurrent.style.opacity = 1;
spotlightBase.style.opacity = 0;
spotlightNext.style.opacity = 0;

// easing
function easeInOutQuad(t){ return t<0.5 ? 2*t*t : -1+(4-2*t)*t; }

function slidePanel(nextIndex, direction='down'){
    if(isAnimating || nextIndex===currentIndex) return;
    isAnimating = true;

    const outgoing = panels[currentIndex];
    const incoming = panels[nextIndex];
    const offscreen = window.innerHeight;
    const isMobile = window.innerWidth <= 768;

    // starting positions
    if (isMobile) {
        incoming.style.bottom = direction==='down' ? `-100%` : `${window.innerHeight}px`;
        incoming.style.top = 'auto';
    } else {
        incoming.style.top = direction==='down' ? `${defaultTop + offscreen}px` : `${defaultTop - offscreen}px`;
        incoming.style.bottom = 'auto';
    }
    incoming.style.opacity = 0;
    incoming.style.zIndex = 3;

    const nextId = panels[nextIndex].querySelector('.section').id;
    spotlightNext.style.backgroundImage = `url(${spotlightImages[nextId] || spotlightImages.welcome})`;
    spotlightNext.style.opacity = 0;

    spotlightBase.style.backgroundImage = `url(${spotlightImages.base})`;
    spotlightBase.style.opacity = 0;

    const startTime = performance.now();
    const duration = 1200;

    function animate(time){
        const t = Math.min((time-startTime)/duration,1);
        const eased = easeInOutQuad(t);

        // slide panels
        if (isMobile) {
            if (direction === 'down') {
                outgoing.style.bottom = `${5 + eased * 105}%`;
            } else {
                outgoing.style.bottom = `${5 - eased * 105}%`;
            }
            outgoing.style.opacity = 1 - Math.pow(eased,0.6);

            if (direction === 'down') {
                incoming.style.bottom = `${-100 + (eased * 105)}%`;
            } else {
                incoming.style.bottom = `${window.innerHeight - (eased * (window.innerHeight - window.innerHeight * 0.1))}px`;
            }
            incoming.style.opacity = Math.pow(eased,0.6);
        } else {
            outgoing.style.top = `${defaultTop + (direction==='down'? -1:1) * eased * offscreen}px`;
            outgoing.style.opacity = 1 - Math.pow(eased,0.6);

            incoming.style.top = `${defaultTop + (direction==='down'?1:-1)*(1-eased)*offscreen}px`;
            incoming.style.opacity = Math.pow(eased,0.6);
        }

        // spotlight: current → base → next
        if(eased < 0.5){
            const p = eased*2; // 0→1
            spotlightCurrent.style.opacity = 1 - p;
            spotlightBase.style.opacity = p;
            spotlightNext.style.opacity = 0;
        } else {
            const p = (eased-0.5)*2; // 0→1
            // Only now is it safe to swap current's source
            if(spotlightCurrent.dataset.swapped !== 'true'){
                spotlightCurrent.dataset.swapped = 'true';
                spotlightCurrent.style.backgroundImage = spotlightNext.style.backgroundImage;
            }
            spotlightCurrent.style.opacity = 0;
            spotlightBase.style.opacity = 1 - p;
            spotlightNext.style.opacity = p;
        }

        if(t<1){
            requestAnimationFrame(animate);
        } else {
            // finalize
            if (isMobile) {
                outgoing.style.bottom = direction==='down'? `105%` : `-105%`;
                outgoing.style.opacity = 0;
                outgoing.style.zIndex = 1;

                incoming.style.bottom = '5%';
                incoming.style.opacity = 1;
                incoming.style.zIndex = 2;
            } else {
                outgoing.style.top = direction==='down'? `${defaultTop - offscreen}px` : `${defaultTop + offscreen}px`;
                outgoing.style.opacity = 0;
                outgoing.style.zIndex = 1;

                incoming.style.top = `${defaultTop}px`;
                incoming.style.opacity = 1;
                incoming.style.zIndex = 2;
            }

            // reset spotlight
            spotlightCurrent.style.opacity = 1;
            spotlightBase.style.opacity = 0;
            spotlightNext.style.opacity = 0;
            spotlightCurrent.dataset.swapped = 'false';

            currentIndex = nextIndex;
            isAnimating = false;
        }
    }

    requestAnimationFrame(animate);
}

// navigation helpers
function nextPanel(){ slidePanel((currentIndex+1)%panels.length,'down'); }
function prevPanel(){ slidePanel((currentIndex-1+panels.length)%panels.length,'up'); }

// wheel / trackpad
window.addEventListener('wheel', e=>{
    if(isAnimating || Math.abs(e.deltaY)<25) return;
    e.deltaY>0 ? nextPanel() : prevPanel();
});

// mobile swipe
let touchStartY = null;
window.addEventListener('touchstart', e=>{ touchStartY=e.touches[0].clientY; });
window.addEventListener('touchend', e=>{
    if(touchStartY===null || isAnimating) return;
    const dy = touchStartY - e.changedTouches[0].clientY;
    if(dy>50) nextPanel();
    else if(dy<-50) prevPanel();
    touchStartY = null;
});
</script>

</body>
</html>