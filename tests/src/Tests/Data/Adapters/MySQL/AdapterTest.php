<?php
namespace Tests\Data;

use Tests\TestCase;

class AdapterTest extends TestCase
{
    public function testMetadata()
    {
        $this->initDatabase('bookshop');

        $adapter = $this->adapter("bookshop");

        // $this->createVariable('variable-133', $adapter->metadata("tables"));
        $this->assertEquals($this->variable('variable-133'), $adapter->metadata("tables"));

        // Users tables
        $users = $adapter->metadata("table", "users");

        $this->assertEquals("bookshop", $users["schema"]);
        $this->assertEquals("users", $users["name"]);
        // $this->assertEquals("BASE", $users["type"]);
        $this->assertEquals("utf8_unicode_ci", $users["collation"]);
        $this->assertEquals("", $users["comment"]);

        // $this->createVariable('variable-135', $adapter->metadata("columns"));
        $this->assertEquals($this->variable('variable-135'), $adapter->metadata("columns"));

        // $this->createVariable('variable-136', $adapter->metadata("column", "users.id"));
        $this->assertEquals($this->variable('variable-136'), $adapter->metadata("column", "users.id"));
    }
}
