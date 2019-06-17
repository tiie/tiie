<?php
namespace Tiie\Http\Headers;

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
            if (strtolower($header->getName()) == $name) {
                return $header;
            }
        }

        return null;
    }

    public function getContentType()
    {
        return $this->get('Content-Type');
    }

    public function toArray()
    {
        $prepared = array();

        foreach ($this->headers as $header) {
            $prepared[$header->getName()] = $header->getValue();
        }

        return $prepared;
    }
}
