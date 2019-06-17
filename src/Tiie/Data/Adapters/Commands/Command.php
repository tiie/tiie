<?php
namespace Tiie\Data\Adapters\Commands;

use Tiie\Data\Adapters\AdapterInterface;
use Tiie\Data\Adapters\Commands\Built;

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
     * @return \Tiie\Data\Adapters\Commands\Command
     */
    abstract public function build(array $params = array()) : Built;

    function __construct(AdapterInterface $adapter = null)
    {
        $this->adapter = $adapter;
    }

    public function setAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    public function getAdapter() : ?AdapterInterface
    {
        return $this->adapter;
    }

    public function execute(array $params = array())
    {
        if (is_null($this->adapter)) {
            throw new \Exception("Adapter is not inject.");
        }

        return $this->adapter->execute($this, $params);
    }

    public function setBind(string $name, $value = null)
    {
        $this->binds[$name] = $value;

        return $this;
    }

    public function setBinds(array $binds) : void
    {
        $this->binds = $binds;
    }

    public function getBinds(array $binds = null) : array
    {
        return $this->binds;
    }

    /**
     * Return unique id a cross all commands.
     *
     * @return string
     */
    protected function getUid() : string
    {
        $uid = self::$uid++;

        return "id{$uid}";
    }
}
