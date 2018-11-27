<?php
namespace Elusim\Response\Engines;

class Text implements \Elusim\Response\Engines\EngineInterface
{
    public function prepare(\Elusim\Response\ResponseInterface $response, \Elusim\Http\Request $request, array $accept)
    {
        return array(
            'code' => $response->code(),
            'body' => empty($response->get("result")) ? "" : $response->get("result"),
            'headers' => $response->headers(),
        );
    }
}
