<?php
namespace Elusim\Http;

class Request
{
    private $method;
    private $urn;
    private $params;
    private $input;
    private $data;
    private $domain;
    private $emergency;
    private $files = array();

    private $id;
    private $chain = null;

    function __construct(
        string $method,
        string $urn,
        array $params = array(),
        array $input = array(),
        array $data = array(),
        string $domain = null,
        int $emergency = 0)
    {
        $this->method = strtolower($method);
        $this->urn = $urn;
        $this->params = $params;
        $this->input = $input;
        $this->data = $data;
        $this->domain = $domain;
        $this->emergency = $emergency;
    }

    public function __toString()
    {
        $string = "";

        if (!empty($this->method)) {
            $method = strtoupper($this->method);
            $string = "{$string} {$method}";
        }

        if (!empty($this->domain)) {
            $string = "{$string} {$this->domain}";
        }

        if (!empty($this->urn)) {
            $string = "{$string}{$this->urn}";
        }

        return trim($string);
    }

    public function files(array $files = null)
    {
        if (is_null($files)) {
            return $this->files;
        } else {
            $this->files = $files;

            return $this;
        }
    }

    public function emergency()
    {
        return $this->emergency;
    }

    public function set($name, $value)
    {
        if (in_array($name, array('headers'))) {
            throw new \Exception("You can not overwrite headers at request.");
        }

        $this->data[$name] = $value;

        return $this;
    }

    public function get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        } else {
            return null;
        }
    }

    /**
     * Set domain or get domain.
     *
     * @param string $domain
     */
    public function domain($domain = null)
    {
        if (is_null($domain)) {
            return $this->domain;
        }else{
            $this->domain = $domain;

            return $this;
        }
    }

    public function method($method = null)
    {
        if (is_null($method)) {
            return $this->method;
        }else{
            $this->method = $method;

            return $this;
        }
    }

    public function urn($urn = null)
    {
        if (is_null($urn)) {
            return $this->urn;
        }else{
            $this->urn = $urn;

            return $this;
        }
    }

    public function chain()
    {
        return $this->chain = clone($this);
    }

    public function params($params = null, int $marge = 1)
    {
        if (is_null($params)) {
            return $this->params;
        }else{
            if ($marge) {
                $this->params = array_merge($this->params, $params);
            } else {
                $this->params = $params;
            }

            return $this;
        }
    }

    public function param($name, $value = null)
    {
        if (is_null($value)) {
            if (!array_key_exists($name, $this->params)) {
                return null;
            }else{
                return $this->params[$name];
            }
        }else{
            $this->params[$name] = $value;

            return $this;
        }
    }

    public function input($name = null)
    {
        if (!is_null($name)) {
            if (is_array($name)) {
                $this->input = $name;
            }else{
                return array_key_exists($name, $this->input) ? $this->input[$name] : null;
            }
        }else{
            return $this->input;
        }
    }

    public function id($id = null)
    {
        if (is_null($id)) {
            return $this->get('id');
        }else{
            return $this->set('id', $id);
        }
    }

    public function lang($lang = null)
    {
        if (is_null($lang)) {
            return $this->get('lang');
        }else{
            return $this->set('lang', $lang);
        }
    }

    public function contentType($contentType = null)
    {
        if (is_null($contentType)) {
            return $this->get('contentType');
        }else{
            return $this->set('contentType', $contentType);
        }
    }

    public function header($name)
    {
        $headers = $this->get('headers');

        if (is_null($headers)) {
            return null;
        }

        return array_key_exists($name, $headers) ? $headers[$name] : null;
    }

    /**
     * Returns IP of request. If IP can not be determined then null is
     * returned.
     *
     * @return string|null
     */
    public function ip() : ?string
    {
        return $this->get('ip');
    }

    public function accept($priorities = array())
    {
        $accept = $this->header('Accept');

        if (is_null($accept)) {
            return null;
        }

        // wykorzystuje zewnetrzna biblioteke do negocjacji
        $negotiator = new \Negotiation\Negotiator();
        // $priorities   = array('text/html; charset=UTF-8', 'application/json', 'application/xml;q=0.5');

        $mediaType = $negotiator->getBest($accept, $priorities);

        return $mediaType->getValue();
    }
}
