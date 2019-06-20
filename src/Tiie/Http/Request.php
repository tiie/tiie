<?php
namespace Tiie\Http;

use UserAgentParser\Exception\NoResultFoundException;
use UserAgentParser\Provider\WhichBrowser;
use UserAgentParser\Model\UserAgent;

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

    private $options;

    function __construct(
        string $method,
        string $urn,
        array $params = array(),
        array $input = array(),
        array $data = array(),
        string $domain = null,
        int $emergency = 0,
        array $options = array()
    ) {
        $this->method = strtolower($method);
        $this->urn = $urn;
        $this->params = $params;
        $this->input = $input;
        $this->data = $data;
        $this->domain = $domain;
        $this->emergency = $emergency;

        $this->options = $options;

        // Init default params.
        if (!empty($this->options["params"]["default"])) {
            foreach ($this->options["params"]["default"] as $name => $value) {
                if (!array_key_exists($name, $this->params)) {
                    $this->params[$name] = $value;
                }
            }
        }
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

    public function setFiles(array $files) : void
    {
        $this->files = $files;
    }

    public function getFiles() : array
    {
        return $this->files;
    }

    public function getEmergency()
    {
        return $this->emergency;
    }

    public function set(string $name, $value)
    {
        if (in_array($name, array('headers'))) {
            throw new \Exception("You can not overwrite headers at request.");
        }

        $this->data[$name] = $value;

        return $this;
    }

    public function get(string $name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        } else {
            return null;
        }
    }

    public function setDomain(string $domain) : void
    {
        $this->domain = $domain;
    }

    public function getDomain() : ?string
    {
        return $this->domain;
    }

    public function setMethod(string $method) : void
    {
        $this->method = $method;
    }

    public function getMethod() : ?string
    {
        return $this->method;
    }

    public function setUrn(string $urn) : void
    {
        $this->urn = $urn;
    }

    public function getUrn()
    {
        return $this->urn;
    }

    public function getAgentParser() : UserAgent
    {
        if (!class_exists(WhichBrowser::class)) {
            throw new Exception("Please include thadafinser/user-agent-parser and whichbrowser/parser to use agent parser.");
        }

        $provider = new WhichBrowser();

        // try {
            $result = $provider->parse($this->getHeader("User-Agent"));
        // } catch (NoResultFoundException $ex){
        //     // nothing found
        // }

        return $result;
    }

    public function chain()
    {
        return $this->chain = clone($this);
    }

    public function setParams($params = null, int $marge = 1) : void
    {
        if ($marge) {
            $this->params = array_merge($this->params, $params);
        } else {
            $this->params = $params;
        }
    }

    public function getParams() : array
    {
        return $this->params;
    }

    public function getFields() : array
    {
        $params = $this->getParams();
        $fields = array();

        foreach ($params as $key => $value) {
            if (strpos($key, "field") === 0) {
                $field = substr($key, 5);
                $field[0] = strtolower($field[0]).substr($key, 1);

                $fields[] = $field;
            }
        }

        return $fields;
    }

    public function setParam($name, $value) : void
    {
        $this->params[$name] = $value;
    }

    public function getParam(string $name)
    {
        return array_key_exists($name, $this->params) ? $this->params[$name] : null;
    }

    public function setInput(array $data) : void
    {
        $this->input = $data;
    }

    public function getInput()
    {
        return $this->input;
    }

    public function getInputByName(string $name)
    {
        return array_key_exists($name, $this->input) ? $this->input[$name] : null;
    }

    public function setId($id) : void
    {
        $this->set('id', $id);
    }

    public function getId()
    {
        return $this->get('id');
    }

    public function setLang(string $lang) : void
    {
        $this->set('lang', $lang);
    }

    public function getLang() : ?string
    {
        return $this->get("lang");
    }

    public function getContentType($contentType = null)
    {
        if (is_null($contentType)) {
            return $this->get('contentType');
        }else{
            return $this->set('contentType', $contentType);
        }
    }

    public function getHeader($name)
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
    public function getIp() : ?string
    {
        return $this->get('ip');
    }

    public function getAccept(array $priorities = array())
    {
        $accept = $this->getHeader('Accept');

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
