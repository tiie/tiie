<?php
namespace Tests\Data\Adapters\Commands\SQL;

use Tests\TestCase;

class AdapterTest extends TestCase
{
    // public function testExecute()
    // {
    // }

    public function testFetch()
    {
        $api = $this->api();

        $result = $api->fetch(array(
            'urn' => '/clients',
        ));

        // $this->createVariable('variable-107', $result->code());
        $this->assertEquals($this->variable('variable-107'), $result->code());

        // $this->createVariable('variable-108', $result->headers()->toArray());
        $this->assertEquals($this->variable('variable-108'), $result->headers()->toArray());

        // $this->createVariable('variable-109', $result->data());
        $this->assertEquals($this->variable('variable-109'), $result->data());
    }

    // public function testMetadata()
    // {
    // }

    // public function testLastId()
    // {
    // }
}
