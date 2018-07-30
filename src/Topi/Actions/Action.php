<?php
namespace Topi\Actions;

abstract class Action
{
    public function __construct()
    {
        $this->init();
    }

    /**
     * Action should return response or null if response is unknow.
     *
     * @return \Topi\Response\ResponseInterface
     */
    public function action(\Topi\Http\Request $request, array $params = array())
    {
        return null;
        // return new \Topi\Response\Response($this);
    }

    /**
     * Metoda tworzy obiekt odpowiedzi na postawie przychodzących danych.
     *
     * @param array $data
     * @return \Topi\Response\ResponseInterface
     */
    protected function response(\Topi\Http\Request $request, array $data)
    {
        $response = new \Topi\Response\Response($this);
        $response->data($data);

        return $response;
    }

    public function init() {}

    public function forward($uri = null, $method = null, $params = null, $data = null)
    {
        return new \Topi\Forward($this->component('router'), $this->request->chain());
    }

    public function redirect($url)
    {
        $response = new \Topi\Response\Response($this);
        $response->header('Location', $url);
        $response->code(301);

        return $response;
    }
}
