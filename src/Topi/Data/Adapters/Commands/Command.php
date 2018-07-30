<?php
namespace Topi\Data\Adapters\Commands;

/**
 * The abstract representation of the command to the adapter.
 */
abstract class Command
{
    private static $uid = 0;

    private $adapter;
    private $binds = array();

    /**
     * Metoda tworzy obiekt Command reprezentujacy wewnętrzy stan buildera.
     *
     * @param array $params
     * @return \Topi\Data\Adapters\Commands\Command
     */
    abstract public function build(array $params = array());

    function __construct(\Topi\Data\Adapters\AdapterInterface $adapter = null)
    {
        $this->adapter = $adapter;
    }

    public function adapter(\Topi\Data\Adapters\AdapterInterface $adapter = null)
    {
        if (is_null($adapter)) {
            return $this->adapter;
        }else{
            $this->adapter = $adapter;

            return $this;
        }
    }

    public function execute($params = array())
    {
        if (is_null($this->adapter)) {
            throw new \Exception("Adapter is not inject.");
        }

        return $this->adapter->execute($this, $params);
    }

    public function bind($name, $value = null)
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

    protected function uid()
    {
        $uid = self::$uid++;

        return "id{$uid}";
    }
}
