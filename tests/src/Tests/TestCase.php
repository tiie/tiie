<?php
namespace Tests;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function app()
    {
        return new \Topi\App(new \Topi\Config("./src/App/Config/tests.php"));
    }

    public function dir()
    {
        return __DIR__;
    }

    protected function dump($variable)
    {
        file_put_contents(print_r($variable, 1), './dump.txt');

        return $this;
    }

    protected function variable($name)
    {
        $file = sprintf("./src/Tests/variables/%s.php", $name);

        if (!file_exists($file)) {
            throw new \Exception("Brak pliku {$file}");
        }

        return include($file);
    }

    protected function md5($text)
    {
        while(strpos($text, " ") !== false){
            $text = str_replace(" ", "", $text);
        }

        while(strpos($text, "\n") !== false){
            $text = str_replace("\n", "", $text);
        }

        while(strpos($text, "\r") !== false){
            $text = str_replace("\r", "", $text);
        }

        return md5($text);
    }

    protected function string($text)
    {
        while(strpos($text, " ") !== false){
            $text = str_replace(" ", "", $text);
        }

        while(strpos($text, "\n") !== false){
            $text = str_replace("\n", "", $text);
        }

        while(strpos($text, "\r") !== false){
            $text = str_replace("\r", "", $text);
        }

        return $text;
    }
}
