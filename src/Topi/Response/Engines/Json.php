<?php
namespace Elusim\Response\Engines;

class Json implements \Elusim\Response\Engines\EngineInterface
{
    use \Elusim\ComponentsTrait;

    public function prepare(\Elusim\Response\ResponseInterface $response, \Elusim\Http\Request $request, array $accept)
    {
        $headers = $response->headers();
        $headers['Content-Type'] = 'application/json';

        $body = "";

        if (!is_null($response->data())) {
            $body = json_encode($response->data());
        }


        if ($this->components()->defined('@lang')) {
            $body = $this->component("@lang")->translateText('pl', $body);
        }

        return array(
            'code' => $response->code(),
            'body' => $body,
            'headers' => $headers,
        );
    }
}
