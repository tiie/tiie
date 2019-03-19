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

    public function timer()
    {
        $timer = new Timer($this);

        $this->timers[] = $timer;

        return $timer;
    }

    public function save() : int
    {
        foreach ($this->timers as $timer) {
            if ($timer->time() >= $this->params["timers"]["saveLongerThan"]) {
                if(file_put_contents(sprintf("%s/%s.%s.json", $this->params["timers"]["output"]["path"], $timer->time(), $timer->id()), json_encode($timer->timetrace())) === false) {
                    trigger_error("Time trace could not be saved.", E_USER_WARNING);
                }
            }
        }

        return 1;
    }
}
