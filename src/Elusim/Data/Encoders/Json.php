<?php
namespace Elusim\Data\Encoders;

use Elusim\Data\Encoders\Encoder;

class Json extends Encoder
{
    public function encode(array $input = array()) : string
    {
        return json_encode($input);
    }

    public function decode(string $input) : array
    {
        return json_decode($input, 1);
    }
}