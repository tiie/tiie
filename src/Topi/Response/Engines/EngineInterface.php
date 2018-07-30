<?php
namespace Topi\Response\Engines;

interface EngineInterface
{
    public function prepare(\Topi\Response\ResponseInterface $response, \Topi\Http\Request $request, array $accept);
}
