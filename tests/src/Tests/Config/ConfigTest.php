<?php
namespace Tests\Config;

use Tiie\Config;
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

        // $this->createVariable('variable-115', $config->toArray());
        $this->assertEquals($this->variable('variable-115'), $config->toArray());

        $config->merge(__DIR__."/app-config.yaml");

        // $this->createVariable('variable-116', $config->toArray());
        $this->assertEquals($this->variable('variable-116'), $config->toArray());

        $config->merge(__DIR__."/app-config.json");

        // $this->createVariable('variable-117', $config->toArray());
        $this->assertEquals($this->variable('variable-117'), $config->toArray());

        $config->merge(__DIR__."/app-config.php");

        // $this->createVariable('variable-118', $config->toArray());
        $this->assertEquals($this->variable('variable-118'), $config->toArray());
    }
}
