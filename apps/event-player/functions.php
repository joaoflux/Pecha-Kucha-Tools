<?php

/* BO Functions for preparing the Pecha Kucha Event */

// Prepare Pecha Kucha event
function prepareEvent($configFile) {
    $slides = array();
    $event = array();

    // Read Pecha Kucha event config file
    $event = json_decode(file_get_contents($configFile),true);
    
    // Get the number of presentations stated in event config file
    $event['presentations'] = readPresentations($event['count']);

    // Write JS array for event player app
    $js_array = json_encode($event);
    echo $js_array;
};


// Prepare and store Pecha Kucha event to file
function storeEvent($configFile, $dataFile) {
    $slides = array();
    $event = array();

    // Read echa Kucha event config file
    $event = json_decode(file_get_contents($configFile),true);
    
    // Get the number of presentations stated in event config file
    $event['presentations'] = readPresentations($event['count']);

    // Write data file
    $fp = fopen($dataFile, 'w');
    fwrite($fp, json_encode($event));
    fclose($fp);
};

// Read all presentations of Pecha Kucha event
function readPresentations($count){ 
    $folderID = array();
    $presentations = array();
    for ($i = 0; $i < $count; $i++) {
        $folderID[$i] = sprintf("%02d", $i+1);
        $folderPath[$i] = $folderID[$i];
        $presentationConfig[$i] = file_get_contents($folderPath[$i]."/_config.json");
        $presentations[$i] = json_decode($presentationConfig[$i],true);
        $presentations[$i]['slot'] = $folderID[$i];
        $presentations[$i]['slides'] = readSlides($folderPath[$i]);
    };
    return $presentations;
}

// Get file names and file type of slides in presentation
function readSlides($dir){ 
    $allowed_ext = array(".png", ".PNG", ".jpg", ".jpeg", ".mov", ".mp4", ".gif", ".JPG"); 
    $dl = array();
    $dt = array();
    if ($hd = opendir($dir))    { 
        while ($sz = readdir($hd)) {
            $ext = strrchr($sz, '.');
            if ((preg_match("/^\./",$sz)==0) && (in_array($ext,$allowed_ext))) {
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