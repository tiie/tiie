<?php
namespace Topi\Response\Engines;

class Text implements \Topi\Response\Engines\EngineInterface
{
    public function prepare(\Topi\Response\ResponseInterface $response, \Topi\Http\Request $request, array $accept)
    {
        return array(
            'code' => $response->code(),
            'body' => empty($response->get("result")) ? "" : $response->get("result"),
            'headers' => $response->headers(),
        );
    }
}
