<?php
namespace Topi\Data\Adapters\Http;

use Topi\Data\Adapters\AdapterInterface;
use Topi\Data\Adapters\Http\Result as HttpResut;
use Topi\Data\Adapters\MetadataAccessibleInterface;
use Topi\Http\Headers\Parser;

class Adapter implements AdapterInterface
{
    private $encoders;
    private $params = array(
        'url' => null,
        'queryEncodeFormat' => 'x-www-form-urlencoded',

        'headers' => array(),
        'params' => array(),
        // 'userAgent' => null,
    );

    function __construct(array $params = array(), array $encoders = array())
    {
        foreach ($this->params as $key => $value) {
            if (array_key_exists($key, $params)) {
                $this->params[$key] = $params[$key];
            }
        }

        $this->encoders = $encoders;

        // if (is_null($this->params['uri'])) {
        //     $this->params['uri'] = "{$this->params['url']}{$this->params['urn']}";
        // }
    }

    public function execute($command, $params = array())
    {
    }

    public function fetch($command, array $params = array()) : \Topi\Data\Adapters\Result
    {
        if (is_array($command)) {
            if (array_key_exists('headers', $command)) {
                $command['headers'] = array_merge($this->params['headers'], $command['headers']);
            }

            if (array_key_exists('params', $command)) {
                $command['params'] = array_merge($this->params['params'], $command['params']);
            }

            $command['urn'] = array_key_exists('urn', $command) ? $command['urn'] : null;
            $command['data'] = array_key_exists('data', $command) ? $command['data'] : array();
            $command['method'] = array_key_exists('method', $command) ? $command['method'] : 'GET';
        }

        // Get cURL resource
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 1);

        switch (strtoupper($command['method'])) {
        case 'GET':
            // The GET method requests a representation of the specified
            // resource. Requests using GET should only retrieve data.
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
            break;
        case 'HEAD':
            // The HEAD method asks for a response identical to that of a GET
            // request, but without the response body.
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'HEAD');
            break;
        case 'POST':
            // The POST method is used to submit an entity to the specified
            // resource, often causing a change in state or side effects on the
            // server.
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
            break;
        case 'PUT':
            // The PUT method replaces all current representations of the
            // target resource with the request payload.
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
            break;
        case 'DELETE':
            // The DELETE method deletes the specified resource.
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
            break;
        case 'CONNECT':
            // The CONNECT method establishes a tunnel to the server identified
            // by the target resource.
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'CONNECT');
            break;
        case 'OPTIONS':
            // The OPTIONS method is used to describe the communication options
            // for the target resource.
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'OPTIONS');
            break;
        case 'TRACE':
            // The TRACE method performs a message loop-back test along the
            // path to the target resource.
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'TRACE');
            break;
        case 'PATCH':
            // The PATCH method is used to apply partial modifications to a
            // resource.
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
            break;
        default:
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
            break;
        }

        curl_setopt($curl, CURLOPT_URL, $this->prepareURI($command));

        if (!empty($command['data'])) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $this->prepareBody($command));
        }

        // Send the request & save response to $resp
        $response = curl_exec($curl);
        $info = curl_getinfo($curl);

        // curl_close($curl);

        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $responseHeaders = substr($response, 0, $headerSize);
        $responseBody = substr($response, $headerSize);

        $headers = (new Parser())->parse($responseHeaders);

        $contentType = $headers->contentType();

        if (is_null($contentType)) {
            throw new \Exception("Unknown Content-Type.");
        }

        $mediaType = $contentType->mediaType();

        if (is_null($mediaType)) {
            throw new \Exception("Unknown media type for {$contentType->value()}.");
        }

        if (!array_key_exists($mediaType, $this->encoders)) {
            throw new \Exception("Unsupported type of media type {$mediaType}.");
        }

        $data = $this->encoders[$mediaType]->decode($responseBody);

        // return new Response($info['http_code'], $headers, $data, $info);
        return new Result($data, array_merge(array(
            'code' => $info['http_code'],
            'headers' => $headers,
        ), $info));
    }

    private function prepareURI(array $command)
    {
        $uri = "{$this->params['url']}{$command['urn']}";

        return $uri;
    }

    private function prepareBody(array $command)
    {
        // $uri = "{$this->params['url']}";

    }

    public function metadata($object, $id = null)
    {
    }

    public function lastId()
    {
    }
}
