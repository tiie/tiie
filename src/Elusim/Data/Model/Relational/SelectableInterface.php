<?php
namespace Elusim\Data\Model\Relational;

use Elusim\Data\Adapters\Commands\SQL\Select;

interface SelectableInterface
{
    public function select(array $params = array(), array $fields = array(), array $sort = array(), int $size = null, int $page = 0) : Select;
}
