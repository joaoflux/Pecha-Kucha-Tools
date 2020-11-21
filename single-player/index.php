<!DOCTYPE html>

<?php
$slides = array();
$path = getcwd();
$slides = dir_list($path);

function dir_list($dir){ 
    $allowed_ext = array(".png", ".PNG", ".jpg", ".jpeg", ".mov", ".mp4", ".gif", ".JPG"); 
    $dl = array();
    $dt = array();
    if ($hd = opendir($dir))    { 
        while ($sz = readdir($hd)) {
            $ext = strrchr($sz, '.');
            if ((preg_match("/^\./",$sz)==0) && (in_array($ext,$allowed_ext))) {
                //$dl[] = $sz;

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
	<link rel="icon" href="../../../pk-tools/SHARED/favicon.ico" type="image/png" />
	<link rel="stylesheet" type="text/css" media="all" href="../../../pk-tools/single-player/styles.css" />
	<script type="text/javascript" src="../../../pk-tools/SHARED/jquery-3.3.1.min.js"></script>
	<?php 
    if (file_exists ($delayConfig)){
	  	echo "<script type=\"text/javascript\" src=\"_delay.js\"></script>"; 
	} else {
		echo "<script type=\"text/javascript\">var delay_in = \"\";</script>";
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
	<script type="text/javascript" src="../../../pk-tools/single-player/app.js"></script>
	<script type='text/javascript'>
		<?php
			$php_array = $slides;
			$js_array = json_encode($php_array);
			echo "var slides = ". $js_array . ";\n";
		?>

		var parsed = "";


		for (i = 0; i< slides.length; i++) {
			var myobj=  slides[i];
				for (var property in myobj) {
					parsed += property + ": " + myobj[property] + "\n";          
				}
		}                           
		inactivityTime();
	</script>
</body>
</html>