<?php
namespace Tiie\Utils;

class Files
{
    public function getFileExtension($path)
    {
        $exploded = explode('.', $path);

        return $exploded[count($exploded)-1];
    }
}

