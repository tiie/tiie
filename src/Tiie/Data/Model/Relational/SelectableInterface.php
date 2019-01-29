<?php
namespace Tiie\Data\Model\Relational;

use Tiie\Data\Adapters\Commands\SQL\Select;

interface SelectableInterface
{
    public function select(array $params = array(), array $fields = array(), array $sort = array(), int $size = null, int $page = 0) : Select;
}
