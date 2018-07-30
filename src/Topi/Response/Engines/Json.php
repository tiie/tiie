<?php
namespace Topi\Response\Engines;

class Json implements \Topi\Response\Engines\EngineInterface
{
    use \Topi\ComponentsTrait;

    public function prepare(\Topi\Response\ResponseInterface $response, \Topi\Http\Request $request, array $accept)
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
