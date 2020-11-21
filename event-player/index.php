<?php
include $pk_tools.$tool.'/functions.php'; 
?>

<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="cache-control" content="no-cache">
    <meta charset="UTF-8">
    <title>PK Event Player</title>
    <script type='text/javascript' src='<?php echo $pk_tools ?>/_global-resources/jquery-3.3.1.min.js'></script> 
    <link rel="Shortcut Icon" href="<?php echo $pk_tools ?>/_global-resources/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo $pk_tools.$tool ?>/styles.css" />
    <?php 
    if (file_exists ($customStyles)){
      echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"customStyles.css\" />"; 
    }; ?>
  </head>
  <body>
    <ul id="menu">
      Navigation goes here
    </ul>
    
    <div id="controls">
      <input id="play" type="button" value="Play" disabled="disabled" onclick="mode('play');startSlideshow();" />
      <input id="pause" type="button" value="Pause" disabled="disabled" onclick="mode('pause');startSlideshow();" />
      <input id="stop" type="button" value="Stop" disabled="disabled" onclick="mode('stop');startSlideshow();" />
    </div>
      
    <div id="stage" class="on">
      <div id="titleSlide">
        <h1 id="speaker">loading event...</h1>
        <input id="startPlayer" type="button" value="Start Player" class="button" onClick="displayEvent()" />
      </div>
      <div id="clockCounter">
        <canvas id="counter" width="60" height="60"></canvas>
        <div id="img-num"></div>
        <canvas id="clock" width="60" height="60"></canvas>
      </div>
    </div>
<!-- EO  Data on the fly
Only required, if you don't read the event from a stored file 
If you remove this, you also need to edit app.js-->
    <script type="text/javascript">
      var eventData = <?php prepareEvent($eventConfigFile); ?>;
      var parsed = "";
    </script>
<!-- EO  Data on the fly -->

    <script type="text/javascript" src="<?php echo $pk_tools.$tool.'/app.js' ?>"></script>
  </body>
</html>
