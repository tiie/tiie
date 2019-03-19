<?php
namespace Tiie\Utils;

class Files
{
    public function fileExtension($path)
    {
        $exploded = explode('.', $path);

        return $exploded[count($exploded)-1];
    }
}

