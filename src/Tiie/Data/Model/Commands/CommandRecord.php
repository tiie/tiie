<?php declare(strict_types=1);
namespace Tiie\Data\Model\Commands;

use Tiie\Commands\Command;
use Tiie\Data\Model\RecordInterface;

class CommandRecord extends Command
{
    private $record;

    function __construct(RecordInterface $record)
    {
        $this->record = $record;
    }

    public function record()
    {
        return $this->record;
    }
}
