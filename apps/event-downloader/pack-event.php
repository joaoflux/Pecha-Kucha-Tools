<?php
$pathname = "../../downloads/EVENT";
$source = "../../events/DIVEX";
$resources = "resources";

mkdir ( $pathname , 0777 , FALSE );

recurse_copy($source, $pathname);
recurse_copy($resources, $pathname);

// Copying files, overwritting it if necessary
/*copy('resources/index.html', $pathname.'/index.html');
copy('resources/app.js', $pathname.'/app.js');
copy('resources/styles.css', $pathname.'/styles.css');
copy('resources/favicon.ico', $pathname.'/favicon.ico');
copy('resources/jquery-3.3.1.min.js', $pathname.'/jquery-3.3.1.min.js');*/

function recurse_copy($source, $dest)
{
    // Check for symlinks
    if (is_link($source)) {
        return symlink(readlink($source), $dest);
    }

    // Simple copy for a file
    if (is_file($source)) {
        return copy($source, $dest);
    }

    // Make destination directory
    if (!is_dir($dest)) {
        mkdir($dest);
    }

    // Loop through the folder
    $dir = dir($source);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }

        // Deep copy directories
        recurse_copy("$source/$entry", "$dest/$entry");
    }

    // Clean up
    $dir->close();
    return true;
}


?>