<?php
$app = $appDir.$app.'/';
$assets = $appDir.'assets/'; 

$eventDirectory = "../NEW_EVENT";
$templateDirectory ="template";
include $app.'functions.php'; 

$targetDirectory = $eventDirectory;
// Create Pecha Kucha event directory
if(isset($_POST['create'])){
    mkdir ( $targetDirectory , 0777 , FALSE );
    //recurse_copy($templateDirectory, $targetDirectory );
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="cache-control" content="no-cache">
    <meta charset="UTF-8">
    <title>Download <?php echo $yourEvent ?></title>
    <script type='text/javascript' src='<?php echo $assets ?>jquery-3.3.1.min.js'></script> 
    <link rel="Shortcut Icon" href="<?php echo $assets ?>favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo $app ?>styles.css" />   
  </head>
  <body>

<div class='container'>
 <h1>Create <?php echo $eventDirectory ?></h1>
 <form method='post' action=''>
  <input type='submit' name='create' value='Create Event Directory' />
 </form>
<a id="downloadLink" class="<?php if (file_exists($targetFDirectory)) {echo "on";}else {echo "off";} ?>" href="<?php echo $targetDirectory ?>"><?php echo $targetFDirectory ?></a>
</div>

</body>
</html>
