<?php
namespace Tiie\Files;

/**
 * todo :
 * - dodanie obslugi bledow w trakcie operacji na pliku
 * - dodani obslugi slasha i backshlasa
 */
class Dir
{
    private $dir;
    private $path;
    private $name;
    private $filename;
    private $new = false;
    private $content;

    function __construct($path)
    {
        if (!file_exists($path)) {
            $this->new = true;
        }

        $tmp = explode('/', $path);

        $this->filename = $tmp[count($tmp)-1];
        array_pop($tmp);
        $this->dir = implode('/', $tmp);
    }

    public function move($dir, $filename = null)
    {
        if (is_null($filename)) {
            $filename = $this->filename;
        }

        if ($this->new) {
            $this->path = sprintf("%s/%s", $dir, $filename);
        }else{
            if (!is_dir($dir)) {
                mkdir($dir, 0700, true);
            }

            $path = sprintf("%s/%s", $dir, $filename);

            rename($this->path(), sprintf('%s/%s', $dir, $filename));

            $this->path = sprintf('%s/%s', $dir, $filename);
        }

        return $this;
    }

    public function path()
    {
        return sprintf('%s/%s', $this->dir, $this->filename);
    }

    public function dir()
    {
        return $this->dir;
    }

    public function name()
    {
        return $this->name;
    }

    public function copy($dir)
    {
    }

    public function unlink($dir)
    {
    }

    public function content()
    {
    }

    public function put($content)
    {
    }

    public function append($content)
    {
    }
}
