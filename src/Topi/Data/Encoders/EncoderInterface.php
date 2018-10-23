<?php
namespace Topi\Data\Decoders;

interface EncoderInterface
{
    public function encode(array $input = array()) : string;
    public function decode(string $input) : array;
}
