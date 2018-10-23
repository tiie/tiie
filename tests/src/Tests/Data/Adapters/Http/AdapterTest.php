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

        // todo : delete
        die(print_r($response, true));
        // endtodo
    }

    // public function testMetadata()
    // {
    // }

    // public function testLastId()
    // {
    // }
}
