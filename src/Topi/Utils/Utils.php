<?php
namespace Elusim\Utils;

class Files
{
    public function fileExtension($path)
    {
        $path = explode('.');

        return $path[count($path)-1];
    }
}

