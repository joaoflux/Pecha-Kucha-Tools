
<?php 
 $targetFile = $yourEvent.".zip";
// Create ZIP file when form posted with 'create'
if(isset($_POST['create'])){
  $zip = new ZipArchive();
  //$filename = $targetFile;
  $filename = $targetFile;
  if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
    exit("cannot open <$filename>\n");
  }
  createZip($zip,$sourcePath);
  $zip->close();
}

// Create ZIP file from source directory
function createZip($zip,$dir){
  if (is_dir($dir)){
  if ($dh = opendir($dir)){
    while (($file = readdir($dh)) !== false){
      // If file
      if (is_file($dir.$file)) {
        if($file != '' && $file != '.' && $file != '..'){
          $zip->addFile($dir.$file);
        }
        } else {
        // If directory
          if(is_dir($dir.$file) ){
            if($file != '' && $file != '.' && $file != '..' && $file != 'download'){
              // Add empty directory
              $zip->addEmptyDir($dir.$file);
              $folder = $dir.$file.'/';
              // Read data of the folder
              createZip($zip,$folder);
            }
          }
        }
      }
      closedir($dh);
    }
  }
}

// Download Created Zip file
if(isset($_POST['download'])){
  if (file_exists($targetFile)) {
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="'.basename($targetFile).'"');
    header('Content-Length: ' . filesize($targetFile));
    header("Pragma: no-cache"); 
    header("Expires: 0");
    ob_clean();
    flush();
    readfile($targetFile);
    // delete file
    unlink($targetFile);
  } else {
    exit ("file <$targetFile> not found!");
  }
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="cache-control" content="no-cache">
    <meta charset="UTF-8">
    <title>Download <?php echo $yourEvent ?></title>
    <link rel="Shortcut Icon" href="<?php echo $rootPath ?>apps/_global-resources/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo $applicationPath ?>/styles.css" />
    <script type='text/javascript' src='<?php echo $rootPath ?>apps/_global-resources/jquery-3.3.1.min.js'></script>     
  </head>
  <body>

<div class='container'>
 <h1>Download <?php echo $yourEvent ?></h1>
 <form method='post' action=''>
  <input type='submit' name='create' value='<?php if (file_exists($targetFile)) {echo "Update ZIP file";}else {echo "create ZIP file";} ?>' />&nbsp;
 </form>
<a id="downloadLink" class="<?php if (file_exists($targetFile)) {echo "on";}else {echo "off";} ?>" href="<?php echo $targetFile ?>"><?php echo $targetFile ?></a>
</div>

</body>
</html>


