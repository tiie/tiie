<?php
namespace Tests\Config;

use Tiie\Config;
use Tests\TestCase;

class ConfigTest extends TestCase
{
    public function testYaml()
    {
        $config = new Config(__DIR__ . "/configs");
        $config->load("config.yaml");

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
        $config = new Config(__DIR__ . "/configs");
        $config->load("config.json");

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
        $config = new Config(__DIR__ . "/configs");
        $config->load("config.php");

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

    public function testGetKeys()
    {
        $config = new Config(__DIR__ . "/configs");

        $config->load("app-config.json");

        // $this->createVariable("variable-138", $config->getKeys("response.headers"));
        $this->assertEquals($this->getVariable("variable-138"), $config->getKeys("response.headers"));
        $this->assertEquals(array(), $config->getKeys("response.headers.0"));
    }

    public function testIs()
    {
        $config = new Config(__DIR__ . "/configs");

        $config->load("app-config.json");

        $this->assertEquals(true, $config->isDefined('response.lang.priorities.1'));
        $this->assertEquals(false, $config->isDefined('response.lang.priorities.2'));
    }

    public function testIsDefined()
    {
        $config = new Config(__DIR__ . "/configs");

        $config->load("app-config.json");

        $this->assertEquals(true, $config->isDefined('response.lang.priorities.1'));
        $this->assertEquals(false, $config->isDefined('response.lang.priorities.2'));
    }

    public function testDirectiveInclude()
    {
        $config = new Config(__DIR__ . "/configs");

        $config->load("include-config");

        // $this->createVariable("variable-139", $config->toArray());
        $this->assertEquals($this->getVariable('variable-139'), $config->toArray());
    }

    public function testGet()
    {
        $config = new Config(__DIR__ . "/configs");

        $config->load("app-config.json");

        $this->assertEquals("Cache-Control, X-Requested-With, Content-Type", $config->get('response.headers.Access-Control-Allow-Headers'));
        $this->assertEquals("application/json", $config->get('response.engines.default'));
        $this->assertEquals("application/json", $config->get('response.contentType.priorities.0'));
        $this->assertEquals("pl-PL,pl", $config->get('response.lang.priorities.0'));
        $this->assertEquals("en-US,en", $config->get('response.lang.priorities.1'));
        $this->assertEquals(null, $config->get('response.lang.priorities.2'));
    }

    public function testGetArrayAccess()
    {
        $config = new Config(__DIR__ . "/configs");

        $config->load("app-config.json");

        $this->assertEquals("Cache-Control, X-Requested-With, Content-Type", $config['response.headers.Access-Control-Allow-Headers']);
        $this->assertEquals("application/json", $config['response.engines.default']);
        $this->assertEquals("application/json", $config['response.contentType.priorities.0']);
        $this->assertEquals("pl-PL,pl", $config['response.lang.priorities.0']);
        $this->assertEquals("en-US,en", $config['response.lang.priorities.1']);
        $this->assertEquals(null, $config['response.lang.priorities.2']);
    }

    public function testLoad()
    {
        $config = new Config(__DIR__ . "/configs");

        $config->load("app-config.json");

        // $this->createVariable("variable-137", $config->toArray());
        $this->assertEquals($this->getVariable('variable-137'), $config->toArray());
        $this->assertEquals("application/json", $config->get("response.engines.default"));
        $this->assertEquals("Cache-Control, X-Requested-With, Content-Type", $config->get("response.headers.Access-Control-Allow-Headers"));
    }

    public function testMerge()
    {
        $config = new Config(__DIR__."/configs");

        $this->assertEquals($this->getVariable('variable-115'), $config->toArray());

        $config->load("app-config.yaml");

        $this->assertEquals($this->getVariable('variable-116'), $config->toArray());

        $config->merge("app-config.json");

        $this->assertEquals($this->getVariable('variable-117'), $config->toArray());

        $config->merge("app-config.php");

        $this->assertEquals($this->getVariable('variable-118'), $config->toArray());
    }
}
