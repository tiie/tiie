<?php
namespace Topi\Data\Adapters;

interface AdapterInterface
{
    public function execute($command, $params = array());

    // public function fetch($command, string $format = 'all', array $params = array());
    public function fetch($command, array $params = array()) : \Topi\Data\Adapters\Result;

    public function metadata($object, $id = null);
    public function lastId();
}
