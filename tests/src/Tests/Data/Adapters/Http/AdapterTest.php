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

        $response = $api->fetch(array(
            'urn' => '/clients',
        ));
    }

    // public function testMetadata()
    // {
    // }

    // public function testLastId()
    // {
    // }
}
