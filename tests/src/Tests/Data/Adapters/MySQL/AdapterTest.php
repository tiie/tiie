<?php
namespace Tests\Data;

use Tests\TestCase;

class AdapterTest extends TestCase
{
    public function testMetadata()
    {
        $this->initDatabase('bookshop');

        $adapter = $this->getAdapter("bookshop");

        // $this->createVariable('variable-133', $adapter->getMetadata("tables"));
        $this->assertEquals($this->getVariable('variable-133'), $adapter->getMetadata("tables"));

        // Users tables
        $users = $adapter->getMetadata("table", "users");

        $this->assertEquals("bookshop", $users["schema"]);
        $this->assertEquals("users", $users["name"]);
        // $this->assertEquals("BASE", $users["type"]);
        $this->assertEquals("utf8_unicode_ci", $users["collation"]);
        $this->assertEquals("", $users["comment"]);

        // $this->createVariable('variable-135', $adapter->getMetadata("columns"));
        $this->assertEquals($this->getVariable('variable-135'), $adapter->getMetadata("columns"));

        // $this->createVariable('variable-136', $adapter->getMetadata("column", "users.id"));
        $this->assertEquals($this->getVariable('variable-136'), $adapter->getMetadata("column", "users.id"));
    }
}
