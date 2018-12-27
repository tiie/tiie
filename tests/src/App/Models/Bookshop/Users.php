<?php
namespace App\Models\Bookshop;

use Elusim\Data\Model\Model;
use Elusim\Data\Adapters\Commands\SQL\Select;
use Elusim\Data\Adapters\Commands\SQL\Update;
use Elusim\Data\Model\RecordInterface;
use Elusim\Data\Model\ModelInterface;

class Users extends Model
{
    private $db;

    function __construct($db)
    {
        $this->db = $db;
    }

    // public function fetch(array $params = array(), int $limit = null, int $offset = 0) : array
    public function fetch(array $params = array(), array $fields = array(), array $sort = array(), int $size = null, int $page = 0) : array
    {
        $select = (new Select($this->db))
            ->from('users')
            ->columns(array(
                'id',
                'firstName',
                'lastName',
                'countryId',
            ))
            ->page($page, $size)
        ;

        // if (!is_null($limit)) {
        //     $select->limit($limit, $offset);
        // }

        $select->params($params, array(
            'id'
        ));

        return $select->fetch()->data();
    }

    public function save(RecordInterface $record, array $params = array()) : ModelInterface
    {
        if (!is_null($errors = $this->validate($record, ModelInterface::PROCESS_SAVING))) {
            throw new \Elusim\Exceptions\ValidateException($errors);
        }

        $update = (new Update($this->db))
            ->set('firstName', $record->get('firstName'))
            ->equal('id', $record->id())
            ->execute()
        ;

        return $this;
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
