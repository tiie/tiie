<?php
namespace Topi\Data\Adapters\Http;

use Topi\Data\Adapters\Http\Headers\Headers;
use Topi\Data\Adapters\Http\Headers\Header;

class Response
{
    private $code;
    private $headers;
    private $data;
    private $variables;

    function __construct(string $code, Headers $headers, array $data = array(), array $variables = array())
    {
        $this->code = $code;
        $this->headers = $headers;
        $this->data = $data;
        $this->variables = $variables;
    }

    /**
     * Return value of code.
     *
     * @return string|null
     */
    public function code() : ?string
    {
        return $this->code;
    }

    public function header(string $name) : ?Header
    {
        return $this->headers->get($name);
    }

    public function get(string $name)
    {
        if (is_null($this->data)) {
            return null;
        }

        return array_key_exists($name, $this->data) ? $this->data[$name] : null;
    }
}
