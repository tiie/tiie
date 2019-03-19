<?php
namespace Tiie\Performance\Timer;

use Tiie\Performance\Performance;
use stdClass;

class Timer
{
    private $performance;

    private $id;
    private $stack = array();
    private $units = array();
    private $pointer;
    private $root;

    function __construct(Performance $performance)
    {
        $this->id = sprintf("%s.%s", date("Ymd_His"), md5(uniqid("", 1)));
        $this->performance = $performance;
    }

    public function id() : string
    {
        return $this->id;
    }

    public function start(string $name, array $context = array()) : int
    {
        // Get file and line.
        $backtrace = debug_backtrace();
        $caller = array_shift($backtrace);

        $unit = new stdClass;
        $unit->id = md5(sprintf("%s%s%s", time(), $caller["file"], $caller["line"]));
        $unit->name = $name;
        $unit->file = $caller['file'];
        $unit->line = $caller['line'];
        $unit->context = $context;
        $unit->childs = array();
        $unit->start = $this->microtime();
        $unit->stop = null;
        $unit->time = null;

        if (is_null($this->root)) {
            $this->root = $unit;
            $this->pointer = $unit;
        } else {
            // Add childs to present.
            $this->pointer->childs[] = $unit;

            // Add to stack.
            $this->stack[] = $this->pointer;

            // Set new pointer.
            $this->pointer = $unit;
        }

        return 1;
    }

    public function stop() : int
    {
        $this->pointer->stop = $this->microtime();
        $this->pointer->time = ($this->pointer->stop - $this->pointer->start);

        if (empty($this->stack)) {
            return 1;
        } else {
            $this->pointer = array_pop($this->stack);
        }

        return 1;
    }

    private function microtime()
    {
        list($usec, $sec) = explode(" ", microtime());

        return ((float)$usec + (float)$sec);
    }

    public function time() : float
    {
        if (empty($this->root)) {
            trigger_error("There is no timetraces to count time.", E_USER_NOTICE);

            return 0;
        }

        if (is_null($this->root->time)) {
            trigger_error("Timetraces are not closed.", E_USER_NOTICE);

            $this->root->stop = $this->microtime();
            $this->root->time = ($this->pointer->stop - $this->pointer->start);

            return $this->root->time;
        } else {
            return $this->root->time;
        }
    }

    public function timetrace()
    {
        return $this->root;
    }
}
