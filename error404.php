<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>404 Not Found</title>

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
                radial-gradient(circle at 35% 20vh, rgba(0,0,255,0.75) 0%, rgba(0,0,255,0) 33vw),
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
</div>

<div class="section">
    <div class="panel-wrap" id="404" data-spotlight="404">
        <div class="panel panel-clear">
            <div class="_panel-top"></div>
            <div class="_panel-header">404 Not Found</div>
            <div class="_panel-content">
                <br><br>Couldn't find the requested file.
            </div>
        </div>
    </div>
</div>


</body>
</html>
