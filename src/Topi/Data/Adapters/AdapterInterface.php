<?php
namespace Topi\Data\Adapters;

interface AdapterInterface
{
    public function execute($command, $params = array());
    public function fetch($command, $format = 'all', array $params = array());
    public function metadata($object, $id = null);
    public function lastId();
}
