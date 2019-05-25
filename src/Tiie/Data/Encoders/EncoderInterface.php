<?php
namespace Tiie\Data\Encoders;

/**
 * Encoder is responsible for encodes data at specific format. It can be JSON,
 * XML, YAML etc. Encoders are used by Reponse mechanism for example.
 *
 * @package Tiie\Data\Encoders
 */
interface EncoderInterface
{
    /**
     * Encode given input.
     *
     * @param array $input
     *
     * @return string
     */
    public function encode(array $input = array()) : string;

    /**
     * Docode given input.
     *
     * @param string $input
     *
     * @return array
     */
    public function decode(string $input) : array;
}
