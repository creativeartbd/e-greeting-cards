<?php 

$path = "Fonts";
$directory = $path;

if (!is_dir($directory)) {
    exit('Invalid diretory path');
}

$files = array();
foreach (scandir($directory) as $file) {
    if ($file !== '.' && $file !== '..') {
        $files[$file] = str_replace( '-', ' ', $file );
    }
}

echo '<pre>';
print_r($files);
