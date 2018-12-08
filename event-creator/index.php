<?php 
$rootPath = "../../";
$eventDirectory = "pk-events/NEW_EVENT";
$templateDirectory ="template";
include 'functions.php';

$targetDirectory = $rootPath.$eventDirectory;
// Create Pecha Kucha event directory
if(isset($_POST['create'])){
    mkdir ( $targetDirectory , 0777 , FALSE );
    recurse_copy($templateDirectory, $targetDirectory );
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="cache-control" content="no-cache">
    <meta charset="UTF-8">
    <title>Download <?php echo $yourEvent ?></title>
    <link rel="Shortcut Icon" href="<?php echo $rootPath ?>pk-tools/_global-resources/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo $applicationPath ?>styles.css" />
    <script type='text/javascript' src='../_global-resources/jquery-3.3.1.min.js'></script>     
  </head>
  <body>

<div class='container'>
 <h1>Create <?php echo $rootPath.$eventDirectory ?></h1>
 <form method='post' action=''>
  <input type='submit' name='create' value='Create Event Directory' />
 </form>
<a id="downloadLink" class="<?php if (file_exists($targetFDirectory)) {echo "on";}else {echo "off";} ?>" href="<?php echo $targetDirectory ?>"><?php echo $targetFDirectory ?></a>
</div>

</body>
</html>
