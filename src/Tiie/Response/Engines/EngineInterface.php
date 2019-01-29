<?php
namespace Tiie\Response\Engines;

interface EngineInterface
{
    public function prepare(\Tiie\Response\ResponseInterface $response, \Tiie\Http\Request $request, array $accept);
}
