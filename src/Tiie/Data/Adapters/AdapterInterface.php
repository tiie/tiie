<?php
namespace Tiie\Data\Adapters;

interface AdapterInterface
{
    /**
     * @param $command
     * @param array $params
     *
     * @return mixed
     */
    public function execute($command, $params = array());

    /**
     * Run given command and returns result.
     *
     * @param $command
     * @param array $params
     *
     * @return Result
     */
    public function fetch($command, array $params = array()) : Result;

    /**
     * Return last generated ID.
     *
     * @return string
     */
    public function lastId() : string;
}
