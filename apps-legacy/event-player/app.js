$ = jQuery.noConflict(); 
$(document).ready(init());
document.onkeydown = checkKey;

// global vars
var baseURL = window.location;
var eventData;

// Navigation settings
var menuBuilt = false;
var menuVisible = false;
var controlsVisible = false;

// Clock and Counter Settings
var slideDuration = 20000;
var clockRadius = 19;
var clockColor = "#F00";
var clockFill = "#333";
var counterRadius = 22;
var counterColor = "#FFF";
var counterFill = "#000";

// Clock and Couter vars
var clock = document.getElementById('clock');
var clockContext = clock.getContext('2d');
var clockX = clock.width / 2;
var clockY = clock.height / 2;
var clockMode = 'stop';
var clockTime = .314;
var counter = document.getElementById('counter');
var counterContext = counter.getContext('2d');
var counterX = counter.width / 2;
var counterY = counter.height / 2;
counterContext.beginPath();
counterContext.arc(counterX, counterY, counterRadius, 1.5 * Math.PI, 3.5 * Math.PI, false);
counterContext.lineWidth = 2;
counterContext.fillStyle = counterFill;
counterContext.fill();
counterContext.strokeStyle = counterColor;
counterContext.stroke();

// Slidshow vars
var presentationID = -1;
var active = 0;
var activeDuration = 0;
var imgsrc = new Array();
var smode = '';
var updateSlide;
var updateClock;
var wasPaused = false;
var isBreak = false;

// functions
function init() {
    loadEventData();
}

function loadEventData() {
    $.ajax({
        url: "event.json",
        dataType: "text",
        success: function (data) {
            data = $.parseJSON(data);
            eventData = data;
            displayEvent();
        }
    });
}

// navigation and controls functions
function toggleMenu() {
    if (!menuBuilt) {
        buildMenu();
    }
    if (menuVisible) {
        $('#menu').css("visibility", "hidden");
        menuVisible = false;
    } else {
        $('#menu').css("visibility", "visible");
        menuVisible = true;
    }
}

function toggleControls() {
    if (controlsVisible) {
        $('#controls').css("visibility", "hidden");
        controlsVisible = false;
    } else {
        $('#controls').css("visibility", "visible");
        controlsVisible = true;
    }
}

function resetControls() {
    controlsVisible = false;
    document.getElementById("play").disabled = "";
    document.getElementById("play").value = "Play";
    document.getElementById("pause").disabled = "disabled";
    document.getElementById("stop").disabled = "disabled";
}

function checkKey(e) {
    switch (e.keyCode) {
        case 222:  // keydown: ä
            isBreak = false;
            mode('stop');
            $('#clockCounter').css("display", "none");
            clearTimeout(updateSlide);
            clearTimeout(updateClock);
            active = 0;
            activeDuration = 0;
            wasPaused = false;
            clockContext.clearRect(0, 0, 80, 80);
            resetControls();
            if (presentationID + 1 == eventData.count) {
                presentationID = -1;
                displayEvent();
            } else {
                initPresentation(presentationID + 1);
            }
            break;
        case 186:  // keydown: ö
            isBreak = false;
            mode('stop');
            $('#clockCounter').css("display", "none");
            clearTimeout(updateSlide);
            clearTimeout(updateClock);
            active = 0;
            activeDuration = 0;
            wasPaused = false;
            clockContext.clearRect(0, 0, 80, 80);
            resetControls();
            if (presentationID - 1 == -1) {
                presentationID = -1;
                displayEvent();
            } else {
                initPresentation(presentationID - 1);
            }
            break;
        case 77:  // keydown: m
            toggleMenu();
            break;
        case 80:  // keydown: p
            isBreak = false;
            if (smode !== "play") {
                if (presentationID != -1) {
                    mode('play');
                    playPresentation(presentationID);
                    startSlideshow();
                    $('#clockCounter').css("display", "block");
                    if (document.getElementById("videoSlide")) {
                        document.getElementById("videoSlide").play();
                    }
                }
            } else if (smode === "play") {
                mode('pause');
                clockMode = 'pause';
                clearTimeout(updateSlide);
                clearTimeout(updateClock);
                if (document.getElementById("videoSlide")) {
                    document.getElementById("videoSlide").pause();
                } else {
                    active--;
                }
                wasPaused = true;
                startSlideshow();
            }
            break;
        case 219:  // keydown: ü
            mode('pause');
            startSlideshow();
            break;
        case 187:  // keydown: +
            isBreak = false;
            mode('stop');
            $('#clockCounter').css("display", "none");
            clearTimeout(updateSlide);
            clearTimeout(updateClock);
            active = 0;
            activeDuration = 0;
            wasPaused = false;
            clockContext.clearRect(0, 0, 80, 80);
            displayTitleSlide(presentationID);
            //startSlideshow();
            break;
        case 88:  // keydown: x
            toggleControls();
            break;
        case 120:  // keypress: x
            toggleControls();
            break;
        case 66:  // keypress: b
            if (isBreak) {
                isBreak = false;
            } else {
                isBreak = true;
            }
            mode('stop');
            $('#clockCounter').css("display", "none");
            clearTimeout(updateSlide);
            clearTimeout(updateClock);
            active = 0;
            activeDuration = 0;
            wasPaused = false;
            clockContext.clearRect(0, 0, 80, 80);
            displayEvent();
            break;
        case 37:  // arrow left
            if (smode !== 'pause') {
                if (active > 1) {
                    clearTimeout(updateSlide);
                    clearTimeout(updateClock);
                    wasPaused = false;
                    mode('play');
                    clockMode = 'play';
                    activeDuration = 0;
                    clockContext.clearRect(0, 0, 80, 80);
                    active -= 2;
                    startSlideshow();
                }
            }
            break;
        case 39:  // arrow right
            if (smode !== 'pause') {
                if (active != 0) {
                    clearTimeout(updateSlide);
                    clearTimeout(updateClock);
                    wasPaused = false;
                    activeDuration = 0;
                    clockContext.clearRect(0, 0, 80, 80);
                    startSlideshow();
                }
            }
            break;
        default:
            break;
    }
}


// Event & Presentaion functions
function displayEvent() {
    wipeStage();
    var eventTitle = '<h1 id="speaker">' + eventData.event + '</h1><h1 id="speaker">' + eventData.place + '</h1>';
    if (isBreak) {
        var eventBreak = '<h2 id="break">Pause</h2>';
    } else {
        var eventBreak = '';
    }
    var startButton = '<div id="startPlayer" class="button" onClick="initPresentation(0)">Start</div>';
    var startScreen = '<div id="titleSlide">' + eventTitle + eventBreak + startButton + '</div>';
    $("div#activeSlide").replaceWith(startScreen);
}

function initPresentation(ID) {
    if (ID < 0) {
        ID = eventData.count - 1;
    }
    if (ID > eventData.count - 1) {
        ID = 0;
    }
    presentationID = ID;
    //prepairSlideshow(ID);
    preloadSlides(ID);
    displayTitleSlide(ID);
    //console.log(presentationID);
}

function preloadSlides(ID) {
    var presentationPath = baseURL + "/" + eventData.presentations[ID].slot + "/";
    var active = 0;
    imgsrc = new Array();
    var preload = new Array();
    for (var i = 0; i < 20; i++) {
        if (presentationPath + eventData.presentations[ID].slides[i].type != '.mp4') {
            source = presentationPath + eventData.presentations[ID].slides[i].file;
            imgsrc[i] = source;
            preload[i] = new Image;
            preload[i].src = source;
        }
    }
}

function displayTitleSlide(ID) {
    wipeStage();
    var activeSpeaker = '<h1 id="speaker">' + eventData.presentations[ID].speaker + '</h1>';
    var activeTitle = '<h2 id="title">' + eventData.presentations[ID].title + '</h2>';
    var activeLink = '<div id="link"><a href="' + eventData.presentations[ID].link + '">' + eventData.presentations[ID].link + '</a></div>';
    var startButton = '<div id="startPresentation" class="button" onclick="playPresentation(' + ID + ')">Start</div>';
    var titleSlide = '<div id="titleSlide">' + activeSpeaker + activeTitle + activeLink + startButton + '</div>';
    $("div#activeSlide").replaceWith(titleSlide);
}

function playPresentation(ID) {
    var slides = [];
    wipeStage();
    var activeSlide = baseURL + "/" + eventData.presentations[ID].slot + "/" + eventData.presentations[ID].slides[0].file;
    if (wasPaused != true) {
        wipeStage();
        if ((eventData.presentations[presentationID].slides[active].type == '.mov') || (eventData.presentations[presentationID].slides[active].type == '.mp4')) {
            $('#activeSlide').html('<div class="fullscreen-bg"><video autoplay id="videoSlide" class="fullscreen-bg__video"><source src="' + activeSlide + '" type="video/mp4"></video></div>');
        } else {
            $('#activeSlide').html('<div id="slideshow" style="background-image: url(' + activeSlide + ');"></div>');
        }
    }
    document.getElementById("play").disabled = "";
    document.getElementById("play").value = "Play";
    document.getElementById("pause").disabled = "disabled";
    document.getElementById("stop").disabled = "disabled";
}

function mode(param) {
    smode = param;
}

function startSlideshow() {
    if (smode === "play") {
        document.getElementById("play").disabled = "disabled";
        document.getElementById("pause").disabled = "";
        document.getElementById("stop").disabled = "";
        var activeSlide = baseURL + "/" + eventData.presentations[presentationID].slot + "/" + eventData.presentations[presentationID].slides[active].file;
        if (wasPaused != true) {
            if ((eventData.presentations[presentationID].slides[active].type == '.mov') || (eventData.presentations[presentationID].slides[active].type == '.mp4')) {
                $('#activeSlide').html('<div class="fullscreen-bg"><video autoplay id="videoSlide" class="fullscreen-bg__video"><source src="' + activeSlide + '" type="video/mp4"></video></div>');
            } else {
                $('#activeSlide').html('<div id="slideshow" style="background-image: url(' + activeSlide + ');"></div>');
            }
        } else {
            wasPaused = false;
        }
        $("#img-num").html(active + 1);
        active++;
        if (activeDuration === 0) {

            clockContext.clearRect(0, 0, 80, 80);
            clockTime = .314;
            slideShowTimeout = slideDuration;
        } else {
            slideShowTimeout = slideDuration - (activeDuration * 1000);
        }
        clockMode = 'play';
        drawClock();
        if (active < 20) {
            updateSlide = setTimeout("startSlideshow()", slideShowTimeout);
        } else {
            updateSlide = setTimeout("displayTitleSlide(" + presentationID + ")", slideShowTimeout);
            mode('');
            active = 0;
        }
    } else if (smode === "pause") {
        document.getElementById("pause").disabled = "disabled";
        document.getElementById("play").disabled = "";
        document.getElementById("play").value = "Resume";
    } else if (smode === "stop") {
        document.getElementById("play").disabled = "";
        document.getElementById("play").value = "Play";
        document.getElementById("pause").disabled = "disabled";
        document.getElementById("stop").disabled = "disabled";
        document.getElementById("slideshow").src = imgsrc[0];
        active = 0;
    }
    if (active === 21) {
        active = 0;
        toggleControls();
        initPresentation(presentationID + 1);
    }
}

function drawClock() {
    if (clockMode == 'play') {
        clockContext.beginPath();
        clockContext.arc(clockX, clockY, clockRadius, -1.6, clockTime - 1.6, false);
        clockContext.lineWidth = 3;
        clockContext.strokeStyle = clockColor;
        clockContext.stroke();
        if (clockTime < 6.28) {
            updateClock = setTimeout("drawClock()", 1000);
            clockTime += .314;
        } else {
            clockMode = 'stop';
            clockTime = 0;
        }
        if (activeDuration < 19) {
            activeDuration++;
        } else {
            activeDuration = 0;
        }
    }
}

function wipeStage() {
    $("div#titleSlide").replaceWith('<div id="activeSlide"></div>');
}