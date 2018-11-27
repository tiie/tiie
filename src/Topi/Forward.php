<?php
namespace Elusim;

class Forward
{
    private $router;
    private $request;

    function __construct($router, $request)
    {
        $this->router = $router;
        $this->request = $request;
    }

    public function uri($uri)
    {
        $this->request->uri($uri);

        return $this;
    }

    public function run()
    {
        return $this->router->dispatch($this->request);
    }
}
