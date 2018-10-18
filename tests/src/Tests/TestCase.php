<?php
namespace Tests;

use Topi\Data\Adapters\Http\Adapter as AdapterHttp;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    private $adapters = array();

    protected function app()
    {
        return new \Topi\App(new \Topi\Config("./src/App/Config/tests.php"));
    }

    protected function api()
    {
        return new AdapterHttp(array(
            'url' => 'localhost',
        ));
    }

    protected function adapter(string $name) {
        if (!array_key_exists($name, $this->adapters)) {
            if ($name == 'bookshop') {
                $this->adapters[$name] = new \Topi\Data\Adapters\Mysql\Adapter(array(
                    'host' => 'localhost',
                    'dbname' => 'bookshop',
                    'username' => 'root',
                    'password' => '',
                    'charset' => 'utf8',
                ));
            }
        }

        return $this->adapters[$name];
    }

    protected function initDatabase(string $name)
    {
        $commands = file_get_contents('./databases/bookshop.sql');

        $this->adapter($name)->execute($commands);
    }

    // protected function fetch()

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

    protected function createVariable($name, $variable)
    {
        file_put_contents(sprintf("./src/Tests/variables/%s.php", $name), sprintf("<?php return %s;", var_export($variable, 1)));
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
