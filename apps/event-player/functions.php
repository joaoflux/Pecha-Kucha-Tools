<?php

/* BO Functions for preparing the Pecha Kucha Event */

$readSlides = array(".png", ".PNG", ".jpg", ".jpeg", ".mov", ".mp4", ".gif", ".JPG");


// Prepare Pecha Kucha event
function prepareEvent($configFile,$files) {
    $slides = array();
    $event = array();

    // Read Pecha Kucha event config file
    $event = json_decode(file_get_contents($configFile),true);
    
    // Get the number of presentations stated in event config file
    $event['presentations'] = readPresentations($event['count'],$files);

    // Write JS array for event player app
    $js_array = json_encode($event);
    echo $js_array;
};

// Read all presentations of Pecha Kucha event
function readPresentations($count,$files){ 
    $folderID = array();
    $presentations = array();
    for ($i = 0; $i < $count; $i++) {
        $folderID[$i] = sprintf("%02d", $i+1);
        $folderPath[$i] = $folderID[$i];
        $presentationConfig[$i] = file_get_contents($folderPath[$i]."/_config.json");
        $presentations[$i] = json_decode($presentationConfig[$i],true);
        $presentations[$i]['slot'] = $folderID[$i];
        $presentations[$i]['slides'] = listFiles($folderPath[$i],$files);
    };
    return $presentations;
}

// Get file names and file type of slides in presentation
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

/* EO Functions for preparing the Pecha Kucha Event */

?>