<?php
namespace Tiie\Performance;

use Tiie\Performance\Timer\Timer;

class Performance
{
    private $params;
    private $timers = array();

    function __construct(array $params = array())
    {
        $this->params = $params;
    }

    public function getTimer() : Timer
    {
        $timer = new Timer($this);

        $this->timers[] = $timer;

        return $timer;
    }

    public function save() : void
    {
        foreach ($this->timers as $timer) {
            if ($timer->getTime() >= $this->params["timers"]["saveLongerThan"]) {
                if(file_put_contents(sprintf("%s/%s.%s.json", $this->params["timers"]["output"]["path"], $timer->getTime(), $timer->getId()), json_encode($timer->getTimetrace())) === false) {
                    trigger_error("Time trace could not be saved.", E_USER_WARNING);
                }
            }
        }
    }
}
