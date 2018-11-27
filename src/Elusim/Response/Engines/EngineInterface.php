<?php
namespace Elusim\Response\Engines;

interface EngineInterface
{
    public function prepare(\Elusim\Response\ResponseInterface $response, \Elusim\Http\Request $request, array $accept);
}
