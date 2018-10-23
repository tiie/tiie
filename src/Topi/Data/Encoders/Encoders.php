<?php
namespace Topi\Data\Decoders;

use Topi\Data\Encoders\EncodersInterface;

class Encoders implements EncodersInterface
{
    private $encoders = array();

    public function get(string $name) : ?EncoderInterface
    {
        return empty($this->encoders[$name]) ? null : $this->encoders[$name];
    }

    public function set(string $name, EncoderInterface $encoder) : EncodersInterface
    {
        $this->encoders[$name] = $encoder;

        return $this;
    }
}
