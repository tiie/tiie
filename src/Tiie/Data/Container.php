<?php
namespace Tiie\Data;

class Container
{
    protected $data;

    function __construct($data = array())
    {
        $this->data = $data;
    }

    public function get(string $name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }

    public function set(string $name, $value)
    {
        $this->data[$name] = $value;

        return $this;
    }

    public function toArray(string $name)
    {
        return $this->data;
    }

    public function is($with)
    {

    }

    // public function string($with)
    // {
    //     // $offer->get("name")->int();
    //     // $offer->get("name", array(), 'int');

    //     $offer->int("name");
    //     $offer->get("name");
    //     $offer->get("user")->get("name");
    //     $offer->get("emails", array());

    //     $offer->user->name;
    // }

    public function int($with)
    {

    }

    public function merge($with)
    {
        if ($with instanceof \Tiie\Data\Container) {
            $with = $with->toArray();
        }

        foreach ($with as $key => $value) {
            $this->data[$key] = $value;
        }

        return $this;
    }
}
