<?php
namespace Topi\Data\Adapters\Http;

use Topi\Data\Adapters\AdapterInterface;
use Topi\Data\Adapters\MetadataAccessibleInterface;

class Adapter implements AdapterInterface
{
    private $params = array(
        'url' => null,
        'queryEncodeFormat' => 'x-www-form-urlencoded',

        'headers' => array(),
        'params' => array(),
        // 'userAgent' => null,
    );

    function __construct(array $params)
    {
        foreach ($this->params as $key => $value) {
            if (array_key_exists($key, $params)) {
                $this->params[$key] = $params[$key];
            }
        }

        // if (is_null($this->params['uri'])) {
        //     $this->params['uri'] = "{$this->params['url']}{$this->params['urn']}";
        // }
    }

    public function execute($command, $params = array())
    {
    }

    public function fetch($command, $format = 'all', array $params = array())
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

        // todo : delete
        // die(print_r($response, true));
        // endtodo
        // todo : delete
        die(print_r(curl_getinfo($curl), true));
        // endtodo
        // todo : delete
        die(print_r($response, true));
        // endtodo
        // Close request to clear up some resources
        curl_close($curl);
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
