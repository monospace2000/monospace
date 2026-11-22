// create an object for each section of the resume/portfolio

function Section(name, index) {

    //init props
    this.name = name;
    this.index = index;
    this.active = false;
    this.animating = false;
    this.debug = false;

    // DOM elements
    this.element = "#" + this.name; // main DOM element/wrapper
    this.contents = this.element + " .section";

    this.loop = null; // reference to setInterval loop
    this.timeout = 10; // loop update speed
    this.counters = { "x": Math.random() * 10, "y": Math.random() * 10, "z": Math.random() * 10 }
    this.increments = { "x": Math.random() / 50, "y": Math.random() / 50, "z": Math.random() / 50 }
    this.radius = { "x": Math.random() * 10, "y": Math.random() * 10 };
    this.position = { "x": 0, "y": 0 };
    this.rotation = { "x": 0, "y": 0, "z": 0 };
    this.scale = { "x": 1, "y": 1 };
    this.opacity = 1;


    // virtual spatial positioning
    this.rangeSize = 60;
    this.overlap = 20//this.rangeSize / 2;
    this.range = {
        start: (index+1) * this.rangeSize - this.overlap,
        center: (index+1) * this.rangeSize + this.rangeSize / 2,
        end: (index+1) * this.rangeSize + this.rangeSize + this.overlap,
    };




    // event listeners
    /*     document.querySelector(this.element).addEventListener("mouseenter", () => {
            this.active = true;
            gsap.to(this.element, { scale: 1.05, duration: 0.1 });
        });
        document.querySelector(this.element).addEventListener("mouseleave", () => {
            this.active = false;
            gsap.to(this.element, { scale: 1, duration: 0.2 });
        });
     */


    this.isInRange = function (pos) {
        return (pos > this.range.start && pos <= this.range.end);
    }
    this.getDistance = function () {
        return Math.abs(scroller.position - this.range.center);
    }

    this.snapTo = function (direction) {
        if (direction === -1) {
            this.scale.x = this.scale.y = 0;
            this.opacity = 0;
        } else if (direction === 1) {
            this.scale.x = this.scale.y = 2;
            this.opacity = 0;
        } else if (direction === 0) {
            this.scale.x = this.scale.y = 1;
            this.opacity = 1;
        }
        gsap.to(this.element, {
            scale: this.scale.x,
            opacity: this.opacity,
        })
    }


    // update loop
    this.updateLoop = function () {



        if (this.isInRange(scroller.position)) {
            activePanel = this;
            this.active = true;
            //if (scroller.active) {

            //console.log(this.getDistance())

            this.scale.x = this.scale.y = gsap.utils.mapRange(this.range.start, this.range.end, 0, 2, scroller.position);
            this.opacity = 1 - Math.abs(this.scale.x - 1)

            if (!this.animating) {
                gsap.to(this.element, {
                    scale: this.scale.x,
                    opacity: this.opacity,
                    //ease: "none"
                });
            }
            //}
        } else {
            activePanel = null;
            this.active = true;
            gsap.to(this.element, {
                opacity: 0
            })
        }
        /*        
                if (this.active) {
                    // apply 3D transform
                    this.position.x = u - mx / 10;
                    this.position.y = v - my / 10;
                    var transform = "rotateX(" + -my / 45 + "deg) rotateY(" + mx / 45 + "deg) "
        
                    gsap.to(this.element, {
                        x: this.position.x,
                        y: this.position.y,
                    })
                    gsap.to(this.contents, {
                        transform: transform
                    });
                }
        
         */
        //else {

        // }

        /*         if (this.active) {
                    // apply 3D transform
                    this.position.x = u - mx / 10;
                    this.position.y = v - my / 10;
                    var transform = "rotateX(" + -my / 45 + "deg) rotateY(" + mx / 45 + "deg) "
        
                    gsap.to(this.element, {
                        x: this.position.x,
                        y: this.position.y,
                    })
                    gsap.to(this.contents, {
                        transform: transform
                    });
                }
         */

        /*         if (this.isInRange(scroller.position)) {
                    this.active = true;
                    if (scroller.active) {
         */


        // user is scrolling

        // apply scroll position to scale and opacity:
        /*     this.scale.x = this.scale.y = gsap.utils.mapRange(this.range.start, this.range.end, 0, 2, scroller.position);
            this.opacity = 1 - Math.abs(this.scale.x - 1)
            gsap.to(this.element, {
                scale: this.scale.x,
                opacity: this.opacity,
                ease: "none"
            });
*/

        /*                 let distance = Math.abs(scroller.position - this.range.center);
                        if (!this.animating && distance < this.rangeSize / 4) {
                            this.animating = true;
                            scrollObserver.disable()
                            //console.log(this.name, "distance to center:" + distance)
                            gsap.to(scroller, {
                                position: this.range.center,
                                onComplete: ()=>{
                                    this.animating = false;
                                    scrollObserver.enable()
                                }
                            })
                        }
         */

        /*         }else{
                    gsap.to(scroller, {
                        position: this.range.center,
                        onUpdate: ()=>{
                            console.log(this.name, scroller.position);
                        }
                    })
    
                    // user not scrolling
               //     this.snapTo(0);
                }
    
            } else {
                // out of range: hide the panel
                this.active = false;
                this.scale.x = this.scale.y = 0;
                this.opacity = 0;
                gsap.set(this.element, {
                    scale: this.scale.x,
                    opacity: this.opacity
                })
            }
     */



        /* 
        this.counters.x += this.increments.x;
        this.counters.y += this.increments.y;
        this.counters.z += this.increments.z;

        nextX = -mx/20 + Math.cos(this.counters.x) * this.radius.x;
        nextY = -my/20 + Math.sin(this.counters.y) * this.radius.y;
 */
        //this.scale.x = this.scale.y = 1 + Math.sin(this.counters.z);

        // this.setPosition(this.anchor.x + nextX, this.anchor.y + nextY);

    }

    this.startLoop = function () {
        this.loop = setInterval(this.updateLoop.bind(this), this.timeout);
        this.active = true;
    }
    this.endLoop = function () {
        if (this.loop) clearInterval(this.loop);
        this.active = false;
    }

    this.startLoop();




    this.print = function () {
        output = "Object: " + this.name + "\n";
        output += "HTML Element: " + this.element + "\n";
        output += "Active: " + this.active + "\n";

        output += "Range: {" + this.range.start + ", " + this.range.center + ", " + this.range.end + "}\n";

        console.log(output)
    }

}



//output+= "name: " + property +"\n";
