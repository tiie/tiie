<?php
namespace Tiie\Actions;

abstract class Action
{
    public function __construct()
    {
        $this->init();
    }

    /**
     * Action should return response or null if response is unknow.
     *
     * @return \Tiie\Response\ResponseInterface
     */
    public function action(\Tiie\Http\Request $request, array $params = array())
    {
        return null;
        // return new \Tiie\Response\Response($this);
    }

    /**
     * Metoda tworzy obiekt odpowiedzi na postawie przychodzących danych.
     *
     * @param array $data
     * @return \Tiie\Response\ResponseInterface
     */
    protected function response(\Tiie\Http\Request $request, array $data)
    {
        $response = new \Tiie\Response\Response($this);
        $response->data($data);

        return $response;
    }

    public function init() {}

    // public function forward($uri = null, $method = null, $params = null, $data = null)
    // {
    //     return new \Tiie\Forward($this->component('router'), $this->request->chain());
    // }

    public function redirect($url)
    {
        $response = new \Tiie\Response\Response($this);
        $response->header('Location', $url);
        $response->code(301);

        return $response;
    }
}
