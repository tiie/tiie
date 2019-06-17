<?php
namespace Tiie\Data\Adapters\Http;

use Tiie\Http\Headers\Headers;
use Tiie\Http\Headers\Header;

class Result extends \Tiie\Data\Adapters\Result
{
    /**
     * Return value of code.
     *
     * @return string|null
     */
    public function getCode() : ?string
    {
        $variables = $this->getVariables();

        return $variables['code'] ?: null;
    }

    public function getHeader(string $name) : ?Header
    {
        $variables = $this->getVariables();

        if (array_key_exists('headers', $variables)) {
            return null;
        }

        return $variables['headers']->get($name);
    }

    public function getHeaders() : ?Headers
    {
        $variables = $this->getVariables();

        if (array_key_exists('headers', $variables)) {
            return $variables['headers'];
        } else {
            return null;
        }
    }

}
