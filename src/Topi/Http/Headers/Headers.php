<?php
namespace Elusim\Http\Headers;

class Headers
{
    private $headers;

    function __construct(array $headers = array())
    {
        $this->headers = $headers;
    }

    public function get(string $name)
    {
        $name = strtolower($name);

        foreach ($this->headers as $header) {
            if (strtolower($header->name()) == $name) {
                return $header;
            }
        }

        return null;
    }

    public function contentType()
    {
        return $this->get('Content-Type');
    }

    public function toArray()
    {
        $prepared = array();

        foreach ($this->headers as $header) {
            $prepared[$header->name()] = $header->value();
        }

        return $prepared;
    }
}
