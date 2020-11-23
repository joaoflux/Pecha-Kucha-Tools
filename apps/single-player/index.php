<!DOCTYPE html>

<?php
$app = $appDir.$app.'/';
$assets = $appDir.'assets/'; 
$slides = array();
$slidesDir = getcwd();
$readSlides = array(".png", ".PNG", ".jpg", ".jpeg", ".mov", ".mp4", ".gif", ".JPG");
$slides = json_encode(listFiles($slidesDir,$readSlides));

function listFiles($dir,$filter){ 
	$dl = array();
    $dt = array();
    if ($hd = opendir($dir))    { 
        while ($sz = readdir($hd)) {
            $ext = strrchr($sz, '.');
            if ((preg_match("/^\./",$sz)==0) && (in_array($ext,$filter))) {
                $dl[] = [
                    "file" => $sz,
                    "type" => $ext,
                ];
                
            }
        } 
    closedir($hd); 
    } 
    sort($dl);
    return $dl;
}
?>

<html dir="ltr" lang="de-DE">
<head>
	<meta charset="UTF-8" />
	<title>Speaker / Title</title>
	<link rel="icon" href="<?php echo $assets ?>favicon.ico" type="image/png" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo $app ?>/styles.css" />
	<style>
	.play {background: url(<?php echo $assets ?>player-s.png) no-repeat -30px 0;}
	.play:hover {background: url(<?php echo $assets ?>player-s.png) no-repeat -30px -30px;}
	.pause {background: url(<?php echo $assets ?>player-s.png) no-repeat 0 0;}
	.pause:hover {background: url(<?php echo $assets ?>player-s.png) no-repeat 0 -30px;}
	.full {background: url(<?php echo $assets ?>toggle-full-s.png) no-repeat -30px 0;}
	.full:hover {background: url(<?php echo $assets ?>toggle-full-s.png) no-repeat -30px -30px;}
	.window {background: url(<?php echo $assets ?>toggle-full-s.png) no-repeat 0 0;}
	.window:hover {background: url(<?php echo $assets ?>toggle-full-s.png) no-repeat 0 -30px;}
	</style>
	<script type="text/javascript" src="<?php echo $assets ?>jquery-3.3.1.min.js"></script>

	<?php 
    if (file_exists ($delayConfig)){
	  	echo "<script type=\"text/javascript\" src=\"_delay.js\"></script>"; 
	} else {
		echo "<script type=\"text/javascript\">var delay_in = \"\";</script>";
	};
	if (file_exists ('recording.mp3') && file_exists ('recording.ogg') ){
		echo "<script type=\"text/javascript\">var recording = \"".$assets."silence-420sec/recording\";</script>";
  	} else {
		echo "<script type=\"text/javascript\">var recording = \"".$assets."silence-420sec/recording\";</script>";
  };
	
	?>
</head>

<body>
	<div id="slideshow">
		<div id="background">
			<h1 id="speaker"></h1>
			<div id="title"></div>
			<div id="date"></div>
		</div>
		<div id="next-slide"></div>
		<div id="current-slide"></div>			  
		<div id="clockCounter">
			<canvas id="counter" width="60" height="60"></canvas>
			<div id="img-num">0</div>
			<canvas id="clock" width="60" height="60"></canvas>
		</div>			
		<div id="controls">
			<span id="play-pause" class="play"></span>
			<span id="toggle-full" class="full"></span>
		</div>	
	</div>
	<script type="text/javascript" src="<?php echo $app ?>app.js"></script>
	<script type='text/javascript'>
		<?php echo "var slides = ". $slides . ";\n"; ?>
		parseSlides();                          
		inactivityTime();
	</script>
</body>
</html>