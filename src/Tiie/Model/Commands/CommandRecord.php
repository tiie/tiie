<?php declare(strict_types=1);
namespace Tiie\Model\Commands;

use Tiie\Commands\Command;
use Tiie\Model\RecordInterface;

class CommandRecord extends Command
{
    private $record;

    function __construct(RecordInterface $record)
    {
        $this->record = $record;
    }

    public function getRecord()
    {
        return $this->record;
    }
}
