<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Smooth Infinite Scroller</title>
<style>
html, body {
    margin: 0;
    padding: 0;
    overflow: hidden;
    height: 100%;
    font-family: sans-serif;
    touch-action: none;
}
.section {
    position: absolute;
    width: 100vw;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 3em;
    font-weight: bold;
}
.box{
    width: 200px;
    height: 200px;
    position: fixed;
    top: 30%;
    left: 70%;
    z-index: 100;
    background: #ffffff;
}
#one   { background:#e74c3c; }
#two   { background:#3498db; }
#three { background:#2ecc71; }
#four  { background:#f1c40f; }
</style>
</head>
<body>

<div class="section" id="one">One</div>
<div class="section" id="two">Two</div>
<div class="section" id="three">Three</div>
<div class="section" id="four">Four</div>

<div class="box">one</div>

<script>
/* ---------------------------------------------------------
   BASIC SETUP
--------------------------------------------------------- */
const sections = Array.from(document.querySelectorAll('.section'));
const count = sections.length;
let vh = window.innerHeight;

// Y offset of the whole system
let y = 0;

// Input state
let dragging = false;
let lastY = 0;
let lastTime = 0;

// Momentum
let velocity = 0;
const friction = 0.92;

// Resize handler (keeps layout correct)
window.addEventListener("resize", () => {
    vh = window.innerHeight;
    positionSections();
});

const box = document.querySelector('.box');
let lastOpacity = 1;

function getCurrentSectionIndex() {
    // Negative y means scrolling down, positive y scrolling up
    // Compute closest section
    let index = Math.round(-y / vh);  // note the minus sign
    // Wrap for infinite
    index = ((index % count) + count) % count;
    return index;
}

function updateBoxOpacity() {
    let rawIndex = -y / vh;
    let nearest = Math.round(rawIndex);
    let dist = Math.abs(rawIndex - nearest);

    let opacity = 1 - dist * 2; // 1 at center, 0 at halfway to next
    opacity = Math.max(0, Math.min(1, opacity));

    box.style.opacity = opacity;

    if (opacity > lastOpacity && lastOpacity < 0.05) {
        let idx = getCurrentSectionIndex();
        box.textContent = sections[idx].id;
    }

    lastOpacity = opacity;
}


/* ---------------------------------------------------------
   POSITIONING (infinite loop)
--------------------------------------------------------- */
function positionSections() {
    const total = count * vh;

    sections.forEach((s, i) => {
        // Base position
        let pos = i * vh + y;

        // Infinite wrap
        while (pos < -vh)     pos += total;
        while (pos > total-vh) pos -= total;

        s.style.top = pos + "px";
    });

    updateBoxOpacity();

}

/* ---------------------------------------------------------
   DRAGGING / TOUCH
--------------------------------------------------------- */
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

    velocity = dy / dt * 16.66;  // normalize

    lastY = nowY;
    lastTime = now;

    positionSections();
}

function endDrag() {
    dragging = false;
}

/* ---------------------------------------------------------
   WHEEL / TRACKPAD
--------------------------------------------------------- */
document.addEventListener("wheel", e => {
    e.preventDefault();

    // Trackpad: small delta, pixel-precise
    const delta = -e.deltaY;
    y += delta;
    velocity = delta;
    positionSections();
}, { passive:false });

/* ---------------------------------------------------------
   MOMENTUM LOOP
--------------------------------------------------------- */
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

/* ---------------------------------------------------------
   EVENT HOOKS
--------------------------------------------------------- */
document.addEventListener("mousedown", startDrag);
document.addEventListener("mousemove", moveDrag);
document.addEventListener("mouseup", endDrag);
document.addEventListener("mouseleave", endDrag);

document.addEventListener("touchstart", startDrag, { passive:false });
document.addEventListener("touchmove",  moveDrag, { passive:false });
document.addEventListener("touchend",   endDrag);
document.addEventListener("touchcancel", endDrag);

/* Initial paint */
positionSections();
</script>

</body>
</html>
