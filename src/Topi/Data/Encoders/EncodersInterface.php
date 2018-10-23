<?php
namespace Topi\Data\Decoders;

use Topi\Data\Encoders\EncoderInterface;

interface EncodersInterface
{
    public function get(string $name) : ?EncoderInterface;
    public function set(string $name, EncoderInterface $encoder) : EncodersInterface;
}
