<?php
namespace Tiie\Performance;

use Tiie\Performance\Timer\Timer;

trait TimerTrait {

    /**
     * @return Timer
     */
    protected function timer() : Timer
    {
        global $components;

        return $components->get("@performance.timer");
    }
}
