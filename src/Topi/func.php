<?php
namespace Topi\func;

function fileExtension($path){
    $texploded = explode('.', $path);

    return $texploded[count($texploded)-1];
}

function scandirr($dir) {
    $files = array();

    foreach (\scandir($dir) as $file) {
        if (in_array($file, array('.', '..'))) {
            continue;
        }

        $path = sprintf("%s/%s", $dir, $file);

        if (is_dir($path)) {
            $files = array_merge($files, scandirr($path));
        }else{
            $files[] = $path;
        }
    }

    return $files;
}

function lang($text, $lang) {
    if (is_array($text)) {
        if (array_key_exists($lang, $text)) {
            return $text[$lang];
        }else{
            $t = array_keys($text);
            $t = $t[0];

            return $text[$t];
        }
    }else{
        return $text;
    }
}
