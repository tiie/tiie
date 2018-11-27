<?php
namespace Elusim\Data\Adapters\Http;

class Result extends \Elusim\Data\Adapters\Result
{
    /**
     * Return value of code.
     *
     * @return string|null
     */
    public function code() : ?string
    {
        $variables = $this->variables();

        return $variables['code'] ?: null;
    }

    public function header(string $name) : ?\Elusim\Http\Headers\Header
    {
        $variables = $this->variables();

        if (array_key_exists('headers', $variables)) {
            return null;
        }

        return $variables['headers']->get($name);
    }

    public function headers() : ?\Elusim\Http\Headers\Headers
    {
        $variables = $this->variables();

        if (array_key_exists('headers', $variables)) {
            return $variables['headers'];
        } else {
            return null;
        }
    }

}
