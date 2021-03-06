<?php
namespace Tiie\Data\Encoders;

/**
 * JSON encode for php. Encode uses json_* php inner functions.
 *
 * @package Tiie\Data\Encoders
 */
class Json extends Encoder
{
    /**
     * {@inheritDoc}
     */
    public function encode(array $input = array()) : string
    {
        return json_encode($input);
    }

    /**
     * {@inheritDoc}
     */
    public function decode(string $input) : array
    {
        return json_decode($input, 1);
    }
}