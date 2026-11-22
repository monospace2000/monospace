gsap.registerPlugin(Draggable);
gsap.registerPlugin(Observer);
gsap.set(window, { scrollTo: 0 });

var gp = gsap.getProperty;


// get mouse coordinates (x, y) relative to center of viewport (u, v)
var u, v, leftEdge, rightEdge, gridDiv, padding;
u = innerWidth / 2;
v = innerHeight / 2;
gridDiv = innerWidth / 10;
leftEdge = gridDiv * 1; // left edge for main content
rightEdge = gridDiv * 4;
padding = 40; // padding

window.addEventListener("resize", e => {
    u = innerWidth / 2;
    v = innerHeight / 2;
});

var mx = u; var my = v;
window.addEventListener("mousemove", e => {
    mx = e.pageX - u;
    my = e.pageY - v;

    // tooltip follows mouse:
    if (tooltip.visible && tooltip.followMouse) {
        gsap.to(tooltip.element, {
            x: mx + u,
            y: my + v,
            duration: 0.2
        })
    }
});






/////////////////////////////////   UI DEV TOOLS
const tooltip = {
    element: "#tooltip",
    HTMLelement: "tooltip",
    menuItem: "menu-tooltip",
    menuItem2: "menu-tooltip-follow",
    visible: false,
    followMouse: false,
    toggle: function () {
        if (this.visible) this.hide();
        else this.show();
    },
    show: function () {
        gsap.to(this.element, { autoAlpha: 1 });
        document.getElementById(this.menuItem).innerHTML = "Hide Tooltip";
        this.visible = true;
    },
    hide: function () {
        gsap.to(this.element, { autoAlpha: 0 });
        document.getElementById(this.menuItem).innerHTML = "Show Tooltip";
        this.visible = false;
    },
    setMessage: function (msg) {
        document.getElementById(tooltip.HTMLelement).innerHTML = msg;
    },
    toggleFollow: function () {
        if (this.followMouse) this.unfollow();
        else this.follow();
    },
    follow: function () {
        document.getElementById(this.menuItem2).innerHTML = "Unfollow Mouse";
        this.followMouse = true;
    },
    unfollow: function () {
        document.getElementById(this.menuItem2).innerHTML = "Follow Mouse";
        this.followMouse = false;
    },
    setPosition: function () {
        gsap.set(this.element, {
            x: mx - u,
            y: my - v,
        })
        setTimeout(() => { this.hide() }, 2000)
    },
    init: function () {
        gsap.set(this.element, {
            x: u,
            y: v,
            xPercent: -100,
            yPercent: -100,
            zIndex: 1200,
        });
        this.hide();
    }
}
tooltip.init();




const crosshairs = {
    element: "#crosshairs",
    menuItem: "menu-crosshairs",
    visible: false,
    toggle: function () {
        if (this.visible) this.hide();
        else this.show();
    },
    show: function () {
        gsap.to(this.element, { autoAlpha: 1 });
        document.getElementById(this.menuItem).innerHTML = "Hide Crosshairs";
        this.visible = true;
    },
    hide: function () {
        gsap.to(this.element, { autoAlpha: 0 });
        document.getElementById(this.menuItem).innerHTML = "Show Crosshairs";
        this.visible = false;
    },
    init: function () {
        gsap.set(this.element, {
            x: u,
            y: v,
            xPercent: -50,
            yPercent: -50
        });
        this.hide();
    }
}
crosshairs.init();



const feedback = {
    element: "#feedback",
    HTMLelement: "feedback", // no poundsign
    menuItem: "menu-feedback",
    visible: false,
    toggle: function () {
        if (this.visible) this.hide();
        else this.show();
    },
    show: function () {
        gsap.to(this.element, { autoAlpha: 1 });
        document.getElementById(this.menuItem).innerHTML = "Hide Log";
        this.visible = true;
    },
    hide: function () {
        gsap.to(this.element, { autoAlpha: 0 });
        document.getElementById(this.menuItem).innerHTML = "Show Log";
        this.visible = false;
    },
    setMessage: function (msg) {
        // not sure why this needs a direct reference to the object, instead of "this": 
        document.getElementById(feedback.HTMLelement).innerHTML = msg;
        if (!feedback.visible) feedback.show();
    },
    getMessage: function () {
        return document.getElementById(this.HTMLelement).innerHTML;
    },
    clear: function () {
        document.getElementById(this.HTMLelement).innerHTML = "";
    },
    init: function () {
        this.setMessage("I'm draggable")
        Draggable.create(this.element, {
            onClick: () => {
                if (this.getMessage() === "I'm draggable") this.clear();
            }
        });
        this.hide();
    }
}
feedback.init();
// shortcut:
log = feedback.setMessage;













// create the background gradient
const container = ".container";
const gradient1 = "radial-gradient(circle at " + u + "px 300px, rgba(0, 0, 255, 0.75) 0px, rgba(0, 0, 255, 0) 540px) fixed no-repeat";
const gradient2 = "radial-gradient(circle at " + (leftEdge + padding) + "px 0px, rgba(0, 0, 255, 0.75) 0px, rgba(0, 0, 255, 0) 320px) fixed no-repeat";
// need to set it with gsap in order to animate:
gsap.set(container, {
    background: gradient1,
});


// position the logo
const logo = ".logo";
gsap.set(logo, {
    x: u,
    y: v - 100,
    xPercent: -50,
    yPercent: -50
})












// references to elements
const pageName = ".page-name";
const pageTitle = ".page-title";
const sectionPanes = ".section";
const sectionWraps = ".section-wrap";
const spotlight = ".spotlight";


gsap.set(spotlight, {
    left: gridDiv * 6
})

gsap.set([pageName, pageTitle], {
    xPercent: -50,
    yPercent: -50,
    x: u,
    y: v
})



// create objects for tracking
var sections = [];
sections.push(new Section("introduction", 0));
sections.push(new Section("objective", 1));
sections.push(new Section("contact", 2));
sections.push(new Section("games", 3));
sections.push(new Section("packages", 4));
sections.push(new Section("exclusives", 5));
sections.push(new Section("tvpath", 6));

var introduction = sections[0];
var objective = sections[1];
var contact = sections[2];
var games = sections[3];
var packages = sections[4];
var exclusives = sections[5];
var tvpath = sections[6];




//var pch = sections["pch"];


// initialize the DOM elements
gsap.set(sectionWraps, {
    xPercent: -50,
    yPercent: -50,
    x: u,
    y: v,
    scale: 0,
    opacity: 0
    //    x: leftEdge + gp(sectionPanes, "width")/2,
    //autoAlpha: 0
})
















var scroller = {
    active: false,
    animating: false,
    position: 0,
    direction: "",
    range: { start: 0, end: 400 },
    currentSection: 0,
    nextSection: 0,
    gotoSection: (section) => {
        speed = Math.abs(scroller.currentSection - section)
        gsap.to(scroller, {
            position: sections[section].range.center,
            duration: speed * 0.5,
            onComplete: () => {
                scroller.currentSection = section;
                scroller.animating = false;
            }
        });
    }
}


var scrollObserver = Observer.create({
    id: 'scrollObserver',
    target: container,
    scrollSpeed: 2,
    wheelSpeed: 2,
    type: "wheel,touch, pointer",
    tolerance: 10,
    preventDefault: true,

    onUp: (self) => {
        if (!scroller.animating && scroller.currentSection > 0) {
            scroller.animating = true;
            scroller.nextSection--;
            scroller.gotoSection(scroller.nextSection)
        }
        return;

        scroller.direction = "up";
        if (scroller.position > scroller.range.start) scroller.position--;
        if (scroller.position == scroller.range.start) console.log("Reached start of scrolling range!");
    },
    onDown: (self) => {
        if (!scroller.animating && scroller.currentSection < sections.length + 1) {
            scroller.animating = true;
            scroller.nextSection++;
            scroller.gotoSection(scroller.nextSection);
        }
        return;

        scroller.direction = "down";
        if (scroller.position < scroller.range.end) scroller.position++;
        if (scroller.position == scroller.range.end) console.log("Reached end of scrolling range!");
    },
    onChange: (self) => {
        scroller.active = true;
        // console.log(scroller.direction, self.deltaY)
        log(scroller.currentSection)
    },
    onStop: (self) => {
        // console.log("Scroll position", scroller.position)
        // console.log("Scroll direction", scroller.direction)

        // console.log("last event:", self.event);
        scroller.direction = "";
        scroller.active = false;
    },






    /* onChange: (self) =>  {
        var opacity = opacityClamper(self.deltaY) 
        var scale = scaleClamper(self.deltaY) 
        gsap.to(introduction.element, {
            //autoAlpha: opacity 
            scale: 1+ scale 
        })

        //console.log(self.velocityY);
           if (introduction.active){
            introduction.scale.x = introduction.scale.y = scaleClamper(introduction.scale.x + self.deltaY/100);
            //console.log(introduction.scale.x)
            gsap.to(introduction.element, {
                scale: introduction.scale.x,
                filter: 1-"blur("+ introduction.scale.x +"px)",
                autoAlpha: 1-introduction.scale.x 
            })
        }

         console.log("velocity:", self.velocityX, self.velocityY, 
            "delta:", self.deltaX, self.deltaY, 
            "target element:", self.target, 
            "last event:", self.event);
       
    }
     */
});
























//////////////////////////////////////////// create a timeline
var tl = gsap.timeline({
    delay: 0.5,
    defaults: {
        duration: 1,
        ease: "power3"
    }
})
    // fade up everything
    .to(container, {
        opacity: 1,
        duration: 0.5
    })

    // fade in logo
    .fromTo(logo, {
        autoAlpha: 0,
        scale: 0.1,
        filter: "blur(10px)",
        x: u,
        y: 500,
    }, {
        autoAlpha: 1,
        scale: 0.8,
        filter: "blur(0px)",
        x: u,
        y: 300,
    }, "<")

    // fade out logo 
    .to(logo, {
        autoAlpha: 0,
        y: -200,
        scale: 4,
        filter: "blur(10px)",
        duration: 2
    }, ">+=1")

    .addLabel("showName")

    // fade up name
    .fromTo(pageName, {
        autoAlpha: 0,
        scale: 1,
        y: 500
    }, {
        y: 250,
        scale: 3,
        autoAlpha: 1,
        duration: 2,
        ease: CustomEase.create("custom", "M0,0 C0,0 0.111,1.304 0.672,1.146 0.828,1.102 1,1 1,1 ")
    }, "<")

    // fade up title
    .fromTo(pageTitle, {
        autoAlpha: 0,
        scale: 0.5,
        y: 550
    }, {
        y: 350,
        scale: 1.5,
        autoAlpha: 1,
        duration: 1,
        ease: "power2.out"
    }, "<+=1.2")

    .addLabel("moveName")

    // move to upper left
    .to([pageName, pageTitle], {
        delay: 1,
        scale: 1,
        x: leftEdge,
        y: padding,
        xPercent: 0,
        yPercent: 0,
        ease: "power3.out",
    })
    .to(pageTitle, {
        y: padding * 1.4 + gp(pageName, "height")
    }, "<")


    // move the gradient
    .to(container, {
        background: gradient2,
        backgroundAttachment: "fixed"
    }, "<")


    .addLabel("showSpotlight")

    // show the spotlight
    .fromTo(".spotlight .floor", {
        autoAlpha: 0
    }, {
        autoAlpha: 1
    })
    .fromTo(".spotlight .beam", {
        autoAlpha: 0
    }, {
        autoAlpha: 1
    }, "<+=0.5")
    .fromTo([".spotlight .object", ".spotlight .shadow"], {
        autoAlpha: 0
    }, {
        autoAlpha: 1
    }, "<+=0.5")


    .addLabel("showPanes")


    .to(scroller, {
        position: introduction.range.center
    })

/*
// pane scale up and in
.fromTo(introduction.element, {
    autoAlpha: 0,
    scale: 0.1
}, {
    autoAlpha: 1,
    scale: 1,
    x: u - gridDiv,
    ease: "back.out",
    onComplete: function () {
        activePanel = introduction;
        scroller.position = activePanel.rangeSize/2;
        activePanel.startLoop();
    }
})
*/




////////////// END TIMELINE /////////////////////





function showStationeryPanes() {
    gsap.fromTo(sectionPanes, {
        autoAlpha: 0,
        scale: 0.1
    }, {
        autoAlpha: 1,
        scale: 1,
        duration: 0.5,
        ease: "back.out",
        stagger: {
            each: 0.1,
            amount: 2,
        }
    }, "<+=1")
}









// set the copyright footer
document.querySelector(".copyright").innerHTML = "Copyright &copy1998&ndash;" + new Date().getFullYear() + " Hens Breet. All Rights Reserved.";


function showContactInfo() {
    let info = "Hens Breet<br>mailto:hens@monospace.com<br>+1 (212) 518-6243"
    tooltip.setMessage(info);
    tooltip.show();
}







//tl.progress(1)
tl.seek("showPanes").play();