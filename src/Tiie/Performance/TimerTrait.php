<?php
namespace Tiie\Performance;

trait TimerTrait {
    protected function timer()
    {
        global $components;

        return $components->get("@performance.timer");
    }
}
