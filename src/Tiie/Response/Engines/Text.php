<?php
namespace Tiie\Response\Engines;

class Text implements \Tiie\Response\Engines\EngineInterface
{
    public function prepare(\Tiie\Response\ResponseInterface $response, \Tiie\Http\Request $request, array $accept)
    {
        return array(
            'code' => $response->code(),
            'body' => empty($response->get("result")) ? "" : $response->get("result"),
            'headers' => $response->headers(),
        );
    }
}
