<?php
namespace Tiie\Response;

use Tiie\Http\Request;

interface ResponseInterface {

    public function response(Request $request);

    /**
     * Set one of value for repose. Value can be used by template engine to render respose.
     * 
     * @param string $name
     * @param mixed $value
     * 
     * @return ResponseInterface
     */
    public function set(string $name, $value) : ResponseInterface;
    
    /**
     * Return value of given attribute.
     * 
     * @param string $name
     * @return mixed
     */
    public function get(string $name);
    
    /**
     * Set or get data of response.
     * 
     * @param array $data
     * @param int $merge
     * 
     * @return array|\Tiie\Response\ResponseInterface
     */
    public function data(array $data = null, int $merge = 1);

    /**
     * Set or get list of all header assigned to response.
     * 
     * @param array $headers
     * @return array|\Tiie\Response\ResponseInterface
     */
    public function headers(array $headers = null);
    
    /**
     * Set or get specific header.
     * 
     * @param string $name
     * @return string|\Tiie\Response\ResponseInterface
     */
    public function header(string $name, $value = null);
    
    /**
     * Set or get code of response.
     * 
     * @param string $code
     * @return string|\Tiie\Response\ResponseInterface
     */
    public function code(string $code = null);
}
