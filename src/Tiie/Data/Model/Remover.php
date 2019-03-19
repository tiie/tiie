<?php
namespace Tiie\Data\Model;

use Tiie\Data\Adapters\AdapterInterface;

class Remover
{
    private $adapter;

    function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }
}
