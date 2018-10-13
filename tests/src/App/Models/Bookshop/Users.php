<?php
namespace App\Models\Bookshop;

use Topi\Data\Model\Model;
use Topi\Data\Adapters\Commands\SQL\Select;

class Users extends Model
{
    private $db;

    function __construct($db)
    {
        $this->db = $db;
    }

    public function fetch(array $params = array(), int $limit = null, int $offset = 0) : array
    {
        $select = (new Select($this->db))
            ->from('users')
            ->columns(array(
                'id',
                'firstName',
                'lastName',
                'countryId',
            ))
        ;

        if (!is_null($limit)) {
            $select->limit($limit, $offset);
        }

        $select->params($params, array(
            'id'
        ));

        return $select->fetch();
    }
}
