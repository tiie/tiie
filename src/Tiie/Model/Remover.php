<?php
namespace Tiie\Model;

use Tiie\Data\Adapters\AdapterInterface;

class Remover
{
    private $adapter;

    function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }
}
