<?php
namespace App\Models\Bookshop;

use Tiie\Model\Model;
use Tiie\Data\Adapters\Commands\SQL\Select;
use Tiie\Data\Adapters\Commands\SQL\Update;
use Tiie\Model\RecordInterface;
use Tiie\Model\ModelInterface;

use Tiie\Commands\CommandInterface;
use Tiie\Commands\Result\ResultInterface;
use Tiie\Commands\Result\Result;

use Tiie\Model\Commands\SaveRecord as CommandSaveRecord;
use Tiie\Model\Commands\RemoveRecord as CommandRemoveRecord;
use Tiie\Model\Commands\CreateRecord as CommandCreateRecord;
use Tiie\Commands\Exceptions\ValidationFailed;

class Users extends Model
{
    private $db;

    function __construct($db)
    {
        $this->db = $db;
    }

    public function fetch(array $params = array(), array $fields = array(), array $sort = array(), int $size = null, int $page = null) : array
    {
        $select = (new Select($this->db));
        $select->from('users');
        $select->columns(array(
            'id',
            'firstName',
            'lastName',
            'countryId',
        ));
        $select->setPage($page, $size);
        $select->setParams($params, array(
            'id'
        ));

        return $select->fetch()->getData();
    }

    public function run(CommandInterface $command, array $params = array()) : ?ResultInterface
    {
        if ($command instanceof CommandSaveRecord) {
            return $this->runSaveRecord($command, $params);
        } else if ($command instanceof CommandCreateRecord) {
            return $this->runCreateRecord($command, $params);
        } else if ($command instanceof CommandRemoveRecord) {
            return $this->runRemoveRecord($command, $params);
        } else {
            return parent::run($command);
        }
    }

    private function runSaveRecord(CommandSaveRecord $command, array $params = array()) : ?ResultInterface
    {
        $this->validateThrow($command, $params);

        $record = $command->getRecord();

        (new Update($this->db))
            ->set('firstName', $record->get('firstName'))
            ->equal('id', $record->getId())
            ->execute()
        ;

        return new Result(true);

    }

    private function runRemoveRecord(CommandRemoveRecord $command, array $params = array()) : ?ResultInterface
    {
        return null;
    }

    private function runCreateRecord(CommandCreateRecord $command, array $params = array()) : ?ResultInterface
    {
    }

    public function validate(CommandInterface $command, array $params = array()) : ?array
    {
        if ($command instanceof CommandSaveRecord) {
            return $this->validateSaveRecord($command, $params);
        } else if ($command instanceof CommandCreateRecord) {
            return $this->validateCreateRecord($command, $params);
        } else if ($command instanceof CommandRemoveRecord) {
            return $this->validateRemoveRecord($command, $params);
        } else {
            return parent::validate($command, $params);
        }
    }

    private function validateSaveRecord(CommandSaveRecord $command, array $params = array()) : ?array
    {
        return null;
    }

    private function validateCreateRecord(CommandCreateRecord $command, array $params = array()) : ?array
    {
        return null;
    }

    private function validateRemoveRecord(CommandRemoveRecord $command, array $params = array()) : ?array
    {
        return null;
    }
}
