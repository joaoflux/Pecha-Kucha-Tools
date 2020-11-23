$ = jQuery.noConflict(); 

// global vars
var presentationDataFile = "_config.json";
var presentationData = new Array();

// Navigation settings
var controlsVisible = true;

// presentation vars
var speaker;
var topic;
var documentTitle;
var date;
// var delay_in;
var delay_out;
var link;

// slideshow vars
var imagesArr = new Array();
var images = "";
var slides = new Array();
var playing = false;
var extension;
var soundfile;
var pos;
var mins;
var secs;
var timeArr = new Array();
var curTime = 0;
var curImage = 0;

// Clock & Counter Settings
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
var counter = document.getElementById('counter');
var counterContext = counter.getContext('2d');
var counterX = counter.width / 2;
var counterY = counter.height / 2;
counterContext.beginPath();
counterContext.arc(counterX, counterY, counterRadius, 1.5*Math.PI, 3.5*Math.PI, false);
counterContext.lineWidth = 2;
counterContext.fillStyle = counterFill;
counterContext.fill();
counterContext.strokeStyle = counterColor;
counterContext.stroke();

$('#slideshow').mouseout(hideControls);
$('#play-pause').click(playpause);
$('#toggle-full').click(toggleFullscreen);
window.onmousemove = showControls;
document.onkeypress = showControls;


$(document).ready(init);


// functions
function init(){
	loadPresentationData();
	
	initAudio();
	initTimes();
	audioElement.onended = function() {
	    resetShow();
		};
}

function loadPresentationData() {
    //var eventDataFile = "event.json";
    $.ajax({
        url: presentationDataFile,
        dataType: "text",
        success: function (data) {
            data = $.parseJSON(data);
			presentationData = data;
			displayInfo();
		},
		//async: false
	});

	
}

function parseSlides() {
	var parsed = "";
		for (i = 0; i< slides.length; i++) {
			var myobj=  slides[i];
				for (var property in myobj) {
					parsed += property + ": " + myobj[property] + "\n";          
				}
		}
}

function resetShow() {
//	playing = false;
//	curTime = 0;
	curImage = 0;
//	timeArr.length = 0;
	initAudio();
//	initTimes();
	
	$("#play-pause").attr("class", "play");
}

function displayInfo(){
    $('#speaker').html(presentationData.speaker);
    $('#title').html(presentationData.title);
    $('#date').html(presentationData.date);
    $('#info-speaker').html(presentationData.speaker);
	$('#info-title').html(presentationData.title); 
    var linkout="<a href='"+link+"' target='blank'>"+link+"</a>";
    $(linkout).appendTo('#info-link');
    var documentTitle = presentationData.speaker+" / "+presentationData.title;
	$('title').html(documentTitle);
	//alert(presentationData.delay);
}

// Controls functions
function tggleFull(){
	if(full){
		enterFull ();
	}else{
		escapeFull ();
	}
}

function playpause(){
	if(playing){
		stopAudio ();
	}else{
		startAudio ();
	}
}

function displayStartBtn(){
	
}

function toggleControls () {
	if(controlsVisible){
		$('#controls').css("visibility", "hidden");
		controlsVisible = false;
	}else{
		$('#controls').css("visibility", "visible");
		controlsVisible = true;
	}
}
function showControls () {
	$('#controls').css("visibility", "visible");
	controlsVisible = true;
}
function hideControls () {
	$('#controls').css("visibility", "hidden");
	controlsVisible = true;
}

function showTitle () {
	$('#background').css("visibility", "visible");
	titleVisible = true;
}

function toggleFullscreen() {
	var isInFullScreen = (document.fullscreenElement && document.fullscreenElement !== null) ||
		(document.webkitFullscreenElement && document.webkitFullscreenElement !== null) ||
		(document.mozFullScreenElement && document.mozFullScreenElement !== null) ||
		(document.msFullscreenElement && document.msFullscreenElement !== null);
		
	var docElm = document.documentElement;
		if (!isInFullScreen) {
			if (docElm.requestFullscreen) {
				docElm.requestFullscreen();
			} else if (docElm.mozRequestFullScreen) {
				docElm.mozRequestFullScreen();
			} else if (docElm.webkitRequestFullScreen) {
				docElm.webkitRequestFullScreen();
			} else if (docElm.msRequestFullscreen) {
				docElm.msRequestFullscreen();
			}
			$("#toggle-full").attr("class", "window");
		} else {
			if (document.exitFullscreen) {
				document.exitFullscreen();
			} else if (document.webkitExitFullscreen) {
				document.webkitExitFullscreen();
			} else if (document.mozCancelFullScreen) {
				document.mozCancelFullScreen();
			} else if (document.msExitFullscreen) {
				document.msExitFullscreen();
			}
			$("#toggle-full").attr("class", "full");
		}
	}


	var inactivityTime = function () {
		var t;
		window.onload = resetTimer;
		// DOM Events
		document.onmousemove = resetTimer;
		document.onkeypress = resetTimer;
		window.onload = resetTimer;
		window.onmousedown = resetTimer;  // catches touchscreen presses as well      
		window.ontouchstart = resetTimer; // catches touchscreen swipes as well 
		window.onclick = resetTimer;      // catches touchpad clicks as well
		window.onkeypress = resetTimer;   
		window.addEventListener('scroll', resetTimer, true); // improved; see comments
		
		function resetTimer() {
			showControls();
			clearTimeout(t);
			t = setTimeout(hideControls, 3000);
			// 1000 milisec = 1 sec
		}
	};

// Clock & Counter functions
function displayTime (){
	if(mins<10){
		mins="0"+mins;
	}
	if(secs<10){
		secs="0"+secs;
	}
	$('#mins').html(mins);
	$('#secs').html(secs);
}

function drawClock(clockStatus) {
	clockContext.beginPath();
	clockContext.arc(clockX, clockY, clockRadius, -1.6, clockStatus - 1.6, false);
	clockContext.lineWidth = 3;
	clockContext.strokeStyle = clockColor;
	clockContext.stroke();
}

// Presentation Playback functions
function startShow(){
	startAudio ();
}

function startAudio (){
	audioElement.play();
	$("#play-pause").attr("class", "pause");	
	playing = true;
}

function stopAudio (){
	audioElement.pause();
	$("#play-pause").attr("class", "play");	
	playing = false;
}

function initAudio(){
	audioElement = new Audio("");
	document.body.appendChild(audioElement);
	$(audioElement).bind('timeupdate', timeUpdate);
	var canPlayType = audioElement.canPlayType("audio/ogg");
	if(canPlayType.match(/maybe|probably/i)) {
		extension = ".ogg";
	} else {
		extension = '.mp3';
	}
	soundfile = recording+extension;
	audioElement.src = soundfile;
	if ((audioElement.buffered != undefined) && (audioElement.buffered.length != 0)) {
		$(audioElement).bind('progress', audioPreloader);
	}else{
		audioElement.addEventListener('canplay', displayStartBtn);
	}
}

function audioPreloader(){
	var loaded = Math.round((audioElement.buffered.end(0) / audioElement.duration) * 100);
    var status = "Loading Sound "+loaded+"%";
   	if(loaded >= 90){
   		displayStartBtn();
   	}
}

function initTimes(){
	for(var i=0; i<=20; i++){
		var time = Number (delay_in) + i * 20 * 1000;
		timeArr.push(time);
	}
}

function timeUpdate(){
	var rem = parseInt(audioElement.duration - audioElement.currentTime, 10);
	curTime = Math.round(audioElement.currentTime * 1000);
  	pos = (audioElement.currentTime / audioElement.duration) * 100;
  	mins = Math.floor(rem/60,10);
  	secs = rem - mins*60;
  	displayTime();
  	if(curTime > timeArr[curImage]){
  		curImage++;
  		displayImage(curImage);
  	}
  	var imagetime = Math.round((timeArr[curImage] - curTime)/1000);
  	var imagebar = 3*imagetime;
	var imageprogress = 20-imagetime;
	//console.log('-');
	//console.log(curImage);
  	if(imageprogress<0){
  		imageprogress = 0;
  	}
	var clockStatus = imageprogress*Math.PI/10;

  	if(curImage>0 && curImage<21) {
		drawClock(clockStatus);
  	}
  	if(imagetime == 20) {
		clockContext.clearRect(0, 0, 80, 80);
  	}
}

function displayImage(num){
	if (num < 21) {
		$('#background').css("visibility", "hidden");
		var file0 = slides[num-1].file;
		var img0 = "<img src='"+file0+"' alt='"+speaker+" - "+topic+" - "+num+"'>";
		}else {
			var img0 = "";
			$('#background').css("visibility", "hidden");
		}	
	if (num < 20) {
		var file1 = slides[num].file;
		var img1 = "<img src='"+file1+"' alt='"+speaker+" - "+topic+" - "+Number(num+1)+"'>";
	}else {
		var img1 = "";
	}
   	if(num == 0){
		$('#next-slide').html(img1);
   	}else if (num == 21){
   		$('#current-slide').html("");
   		$('#next-slide').html("");
   		$('#clock').css("visibility", "hidden");
		$('#clockCounter').css("visibility", "hidden");
		$('#background').css("visibility", "visible");
   	}else{
   		$('#current-slide').html(img0);
   		$('#next-slide').html(img1);
   		$('#clockCounter').css("visibility", "visible");
   	}
   	$('#img-num').html(curImage);
}

function twoDigits(zahl){
	if (zahl <10){
   		zahl = "0"+zahl;
   	}
   	return zahl;
}