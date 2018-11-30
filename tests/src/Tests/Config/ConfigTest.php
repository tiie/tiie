<?php
namespace Tests\Config;

use Elusim\Config;
use Tests\TestCase;

class ConfigTest extends TestCase
{
    public function testYaml()
    {
        $config = new Config(__DIR__."/config.yaml");

        $this->assertEquals('foo', $config->get('value'));
        $this->assertEquals('boo', $config->get('object.value'));
        $this->assertEquals('a', $config->get('object.list.0'));
        $this->assertEquals('b', $config->get('object.list.1'));
        $this->assertEquals('coo', $config->get('object.list.2.value'));
        $this->assertEquals('boo', $config->get('object.object.value'));
        $this->assertEquals('a', $config->get('object.object.list.0'));
        $this->assertEquals('b', $config->get('object.object.list.1'));
        $this->assertEquals('coo', $config->get('object.object.list.2.value'));
        $this->assertEquals('a', $config->get('list.0'));
        $this->assertEquals('b', $config->get('list.1'));
        $this->assertEquals('coo', $config->get('list.2.value'));
    }

    public function testJson()
    {
        $config = new Config(__DIR__."/config.json");

        $this->assertEquals('foo', $config->get('value'));
        $this->assertEquals('boo', $config->get('object.value'));
        $this->assertEquals('a', $config->get('object.list.0'));
        $this->assertEquals('b', $config->get('object.list.1'));
        $this->assertEquals('coo', $config->get('object.list.2.value'));
        $this->assertEquals('boo', $config->get('object.object.value'));
        $this->assertEquals('a', $config->get('object.object.list.0'));
        $this->assertEquals('b', $config->get('object.object.list.1'));
        $this->assertEquals('coo', $config->get('object.object.list.2.value'));
        $this->assertEquals('a', $config->get('list.0'));
        $this->assertEquals('b', $config->get('list.1'));
        $this->assertEquals('coo', $config->get('list.2.value'));
    }

    public function testPhp()
    {
        $config = new Config(__DIR__."/config.php");

        $this->assertEquals('foo', $config->get('value'));
        $this->assertEquals('boo', $config->get('object.value'));
        $this->assertEquals('a', $config->get('object.list.0'));
        $this->assertEquals('b', $config->get('object.list.1'));
        $this->assertEquals('coo', $config->get('object.list.2.value'));
        $this->assertEquals('boo', $config->get('object.object.value'));
        $this->assertEquals('a', $config->get('object.object.list.0'));
        $this->assertEquals('b', $config->get('object.object.list.1'));
        $this->assertEquals('coo', $config->get('object.object.list.2.value'));
        $this->assertEquals('a', $config->get('list.0'));
        $this->assertEquals('b', $config->get('list.1'));
        $this->assertEquals('coo', $config->get('list.2.value'));
    }

    public function testMerge()
    {
        $config = new Config();

        $merged = $config->arrayMerge(array(
            // 'name' => 'Pawel',
            'emails' => array(
                'a@o2.pl',
                'b@o2.pl',
                'c@o2.pl',
            ),
        ), array(
            // 'lastName' => 'Gaweł',
            'emails' => array(
                'd@o2.pl',
            ),
        ));

        // todo [debug] Debug to delete
        die(print_r($merged, true));
        // $configYaml = new Config(__DIR__."/app-config.yaml");
        // $configJson = new Config(__DIR__."/app-config.json");
        // $configPhp = new Config(__DIR__."/app-config.php");

        // $config = new Config();

        // $config->merge($configYaml);
        // $config->merge($configJson);
        // $config->merge($configPhp);

        // todo [debug] Debug to delete
        die(print_r($config, true));
        // todo [debug] Debug to delete
        // die(print_r(array(
        //     $configYaml,
        //     $configJson,
        //     $configPhp,
        // ), true));
        // // todo [debug] Debug to delete
        // die(print_r($config, true));
    }
    // public function load($config)
    // public function offsetSet($offset, $value)
    // public function offsetExists($offset)
    // public function offsetUnset($offset)
    // public function offsetGet($offset)
    // public function merge($config, $reverse = false)
    // public function export($path)
    // public function defined($key)
    // public function toArray()
    // public function keys($name)
    // public function is($key)
    // public function get($key, $default = null)
    // public function set($key, $value)
    // public function config($key)
}
