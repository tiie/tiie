<?php
namespace Elusim\Actions;

abstract class Action
{
    public function __construct()
    {
        $this->init();
    }

    /**
     * Action should return response or null if response is unknow.
     *
     * @return \Elusim\Response\ResponseInterface
     */
    public function action(\Elusim\Http\Request $request, array $params = array())
    {
        return null;
        // return new \Elusim\Response\Response($this);
    }

    /**
     * Metoda tworzy obiekt odpowiedzi na postawie przychodzących danych.
     *
     * @param array $data
     * @return \Elusim\Response\ResponseInterface
     */
    protected function response(\Elusim\Http\Request $request, array $data)
    {
        $response = new \Elusim\Response\Response($this);
        $response->data($data);

        return $response;
    }

    public function init() {}

    public function forward($uri = null, $method = null, $params = null, $data = null)
    {
        return new \Elusim\Forward($this->component('router'), $this->request->chain());
    }

    public function redirect($url)
    {
        $response = new \Elusim\Response\Response($this);
        $response->header('Location', $url);
        $response->code(301);

        return $response;
    }
}
