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

    public function save(RecordInterface $record, array $params = array()) : ModelInterface
    {
        if (!is_null($errors = $this->validate($record, ModelInterface::PROCESS_SAVING))) {
            throw new \Topi\Exceptions\ValidateException($errors);
        }

    }

    public function validate(RecordInterface $record, string $process, array $params = array()) : ?array
    {
        switch ($process) {
        case ModelInterface::PROCESS_CREATING:
            return null;
        case ModelInterface::PROCESS_SAVING:
            return null;
        default:
            return parent::validate($record, $process, $params);
        }
    }
}
