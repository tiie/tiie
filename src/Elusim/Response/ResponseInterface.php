<?php
namespace Elusim\Response;

interface ResponseInterface {

    public function response(\Elusim\Http\Request $request);

    /**
     * Set data for response.
     *
     * @param string $name
     * @param string $name
     * @return \Elusim\Response\ResponseInterface
     */
    public function set($name, $value);
    public function get($name);
    public function data(array $data = null, int $merge = 1);

    public function headers($headers = null);
    public function header($name, $value = null);
    public function code($code = null);
}