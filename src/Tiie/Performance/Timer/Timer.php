<?php
namespace Tiie\Performance\Timer;

use Tiie\Performance\Performance;
use stdClass;

class Timer
{
    /**
     * @var Performance
     */
    private $performance;

    /**
     * @var string
     */
    private $id;

    /**
     * @var array
     */
    private $stack = array();

    /**
     * @var array
     */
    private $units = array();

    /**
     * @var
     */
    private $pointer;

    /**
     * @var stdClass|null
     */
    private $root;

    function __construct(Performance $performance)
    {
        $this->id = sprintf("%s.%s", date("Ymd_His"), md5(uniqid("", 1)));
        $this->performance = $performance;
    }

    /**
     * Return ID of timer. Each timmer has uniqe id.
     *
     * @return string
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * Start timmer.
     *
     * @param string $name
     * @param array $context
     */
    public function start(string $name, array $context = array()) : void
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
        $unit->start = $this->getMicrotime();
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
    }

    /**
     * Stop timmer.
     */
    public function stop() : void
    {
        $this->pointer->stop = $this->getMicrotime();
        $this->pointer->time = ($this->pointer->stop - $this->pointer->start);

        if (!empty($this->stack)) {
            $this->pointer = array_pop($this->stack);
        }
    }

    private function getMicrotime()
    {
        list($usec, $sec) = explode(" ", microtime());

        return ((float)$usec + (float)$sec);
    }

    public function getTime() : float
    {
        if (empty($this->root)) {
            trigger_error("There is no timetraces to count time.", E_USER_NOTICE);

            return 0.0;
        }

        if (is_null($this->root->time)) {
            trigger_error("Timetraces are not closed.", E_USER_NOTICE);

            $this->root->stop = $this->getMicrotime();
            $this->root->time = ($this->pointer->stop - $this->pointer->start);

            return $this->root->time;
        } else {
            return $this->root->time;
        }
    }

    /**
     * Returns timers stack.
     *
     * @return stdClass|null
     */
    public function getTimetrace()
    {
        return $this->root;
    }
}
