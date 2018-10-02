<?php
namespace Topi\Data\Adapters\Commands;

use Topi\Data\Adapters\AdapterInterface;

/**
 * The abstract representation of the command to the adapter.
 */
abstract class Command
{
    private static $uid = 0;

    private $adapter;
    private $binds = array();

    /**
     * Create build command.
     *
     * @param array $params
     * @return \Topi\Data\Adapters\Commands\Command
     */
    abstract public function build(array $params = array());

    function __construct(AdapterInterface $adapter = null)
    {
        $this->adapter = $adapter;
    }

    public function adapter(AdapterInterface $adapter = null)
    {
        if (is_null($adapter)) {
            return $this->adapter;
        }else{
            $this->adapter = $adapter;

            return $this;
        }
    }

    public function execute(array $params = array())
    {
        if (is_null($this->adapter)) {
            throw new \Exception("Adapter is not inject.");
        }

        return $this->adapter->execute($this, $params);
    }

    public function bind(string $name, $value = null)
    {
        $this->binds[$name] = $value;

        return $this;
    }

    public function binds(array $binds = null)
    {
        if (is_null($binds)) {
            return $this->binds;
        }else{
            $this->binds = $binds;

            return $this;
        }
    }

    protected function uid() : string
    {
        $uid = self::$uid++;

        return "id{$uid}";
    }
}
