
<?php 
// Create ZIP file
if(isset($_POST['create'])){
 $zip = new ZipArchive();
 $filename = "../../downloads/PechaKuchaEvent.zip";

 if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
  exit("cannot open <$filename>\n");
 }

 $dir = '../../downloads/EVENT/';

 // Create zip
 createZip($zip,$dir);

 $zip->close();
}

// Create zip
function createZip($zip,$dir){
 if (is_dir($dir)){

  if ($dh = opendir($dir)){
   while (($file = readdir($dh)) !== false){
 
    // If file
    if (is_file($dir.$file)) {
     if($file != '' && $file != '.' && $file != '..'){
 
      $zip->addFile($dir.$file);
     }
    }else{
     // If directory
     if(is_dir($dir.$file) ){

      if($file != '' && $file != '.' && $file != '..'){

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
 
 $filename = "myzipfile.zip";

 if (file_exists($filename)) {
  header('Content-Type: application/zip');
  header('Content-Disposition: attachment; filename="'.basename($filename).'"');
  header('Content-Length: ' . filesize($filename));

  flush();
  readfile($filename);
  // delete file
  unlink($filename);
 
 }
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="cache-control" content="no-cache">
    <meta charset="UTF-8">
    <title>Prepair Pecha Kucha</title>
    <link rel="Shortcut Icon" href="../../assets/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" media="all" href="../../apps/event-downloader/styles.css" />
    <script type='text/javascript' src='../../assets/jquery-3.3.1.min.js'></script>     
  </head>
  <body>

<div class='container'>
 <h1>Create and Download Zip file using PHP</h1>
 <form method='post' action=''>
  <input type='submit' name='create' value='Create Zip' />&nbsp;
  <input type='submit' name='download' value='Download' />
 </form>
</div>

</body>
</html>

