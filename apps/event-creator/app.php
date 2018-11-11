<!DOCTYPE html>
<html>
<body>

<?php

$txt_file    = file_get_contents('../_config.txt');
$rows        = explode("*", $txt_file);
array_shift($rows);
echo sizeof($rows).'<br/>';
unset($rows[1]);
unset($rows[3]);
unset($rows[5]);
unset($rows[7]);
unset($rows[9]);
unset($rows[11]);
$remove = array("event=", "date=", "place=", "pause1=", "pause2=", "count=");
$keys = array("event", "date", "place", "pause1", "pause2", "count");
$speaker_remove = array("speaker=", "topic=", "date=", "delay_in=", "delay_out=", "link=");
$speaker_keys = array("speaker", "topic", "date", "delay_in", "delay_out", "link");
$values = array();
$event = array();
$folderID = array();
$speaker_file = array();
$speakers = array();
$slides = array();


// print_r($rows);

echo sizeof($rows).'<br/>';
foreach($rows as $row => $data)
{
    $clean = str_replace($remove, "", $data);
    echo '<br/>'.$clean;
    $event[] = $clean;
    
}

// print_r($event);

$foo = array_combine($keys, $event);


echo '<br/><br/>'.$foo['place'].'<br/>';

for ($i = 0; $i < $foo['count']; $i++) {

    $folderID[$i] = sprintf("%02d", $i+1);
    $folderPath[$i] = '../'.$folderID[$i];
    $slides[$i] = dir_list($folderPath[$i]);
    
    print_r($slides[$i]);

    $file_URL = $folderPath[$i].'/_config.txt';
    $speaker_file[$i] = file_get_contents($file_URL);
    $speaker_rows[$i] = explode("*", $speaker_file[$i]);

    unset($speaker_rows[$i][0]);
    $speaker_rows[$i]['speaker'] = $speaker_rows[$i][1];
    unset($speaker_rows[$i][1]);

    unset($speaker_rows[$i][2]);
    $speaker_rows[$i]['title'] = $speaker_rows[$i][3];
    unset($speaker_rows[$i][3]);

    unset($speaker_rows[$i][4]);
    $speaker_rows[$i]['date'] = $speaker_rows[$i][5];
    unset($speaker_rows[$i][5]);

    unset($speaker_rows[$i][6]);
    $speaker_rows[$i]['delay_in'] = $speaker_rows[$i][7];
    unset($speaker_rows[$i][7]);

    unset($speaker_rows[$i][8]);
    $speaker_rows[$i]['delay_out'] = $speaker_rows[$i][9];
    unset($speaker_rows[$i][9]);

    unset($speaker_rows[$i][10]);
    $speaker_rows[$i]['link'] = $speaker_rows[$i][11];
    unset($speaker_rows[$i][11]);

    $speaker_rows[$i]['slot'] = $folderID[$i];

    $speaker_rows[$i]['slides'] = $slides[$i];
    
  

    foreach($speaker_rows as $speaker_row => $data) {
        $speaker_clean = str_replace($speaker_remove, "", $data);
        $event['presentations'][$i] = $speaker_clean;
    
    }
};

print_r($event);


$event['event'] = $event[0];
unset($event[0]);
$event['date'] = $event[1];
unset($event[1]);
$event['place'] = $event[2];
unset($event[2]);
$event['pause1'] = $event[3];
unset($event[3]);
$event['pause2'] = $event[4];
unset($event[4]);
$event['count'] = $event[5];
unset($event[5]);

$fp = fopen('../event.json', 'w');
fwrite($fp, json_encode($event));
fclose($fp);

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

</body>
</html>