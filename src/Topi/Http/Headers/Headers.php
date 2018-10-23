<?php
namespace Topi\Http\Headers;

class Headers
{
    private $headers;

    function __construct(array $headers = array())
    {
        $this->headers = $headers;
    }

    public function get(string $name)
    {
        foreach ($this->headers as $header) {
            if ($header->name() == $name) {
                return $header;
            }
        }

        return null;
    }
}
