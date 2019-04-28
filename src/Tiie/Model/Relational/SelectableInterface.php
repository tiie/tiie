<?php
namespace Tiie\Model\Relational;

use Tiie\Data\Adapters\Commands\SQL\Select;

interface SelectableInterface
{
    public function select(array $params = array(), array $fields = array(), array $sort = array(), int $size = null, int $page = null) : Select;
}
