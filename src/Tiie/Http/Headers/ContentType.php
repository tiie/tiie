<?php
namespace Tiie\Http\Headers;

use Tiie\Http\Headers\Header;

/**
 * @link https://www.ietf.org/rfc/rfc2045.txt
 */
class ContentType extends Header
{
    function __construct(string $value)
    {
        parent::__construct('Content-Type', $value);
    }

    public function setMediaType(string $mediaType) : void
    {
        $decoded = $this->decode($this->getValue());
        $decoded = array_merge($decoded, array('mediaType' => $mediaType));

        $this->setValue($this->encode($decoded));
    }

    public function getMediaType() : ?string
    {
        $decoded = $this->decode($this->getValue());

        return empty($decoded['mediaType']) ? null : $decoded['mediaType'];
    }

    public function setCharset(string $charset) : void
    {
        $decoded = $this->decode($this->getValue());
        $decoded = array_merge($decoded, array('charset' => $charset));

        $this->setValue($this->encode($decoded));
    }

    public function getCharset()
    {
        $decoded = $this->decode($this->getValue());

        return empty($decoded['charset']) ? null : $decoded['charset'];
    }

    /**
     * Setting the 'boundary' value or reading it.
     *
     * @param string $boundary
     * @return $this|string
     */
    public function boundary(string $boundary = null)
    {
        $decoded = $this->decode($this->getValue());

        if (func_num_args() == 0) {
            return empty($decoded['boundary']) ? null : $decoded['boundary'];
        } else {
            $decoded = array_merge($decoded, array('boundary' => $boundary));

            $this->setValue($this->encode($decoded));

            return $this;
        }
    }

    private function decode(string $encoded)
    {
        $decoded = array();

        foreach (explode(';', $this->getValue()) as $key => $value) {
            $value = trim($value);

            if ($key == 0) {
                $decoded['mediaType'] = $value;
                continue;
            }

            $value = explode('=', $value);

            $decoded[trim($value[0])] = trim($value[1]);
        }

        return $decoded;
    }

    private function encode(array $decoded = array())
    {
        $encoded = "";

        if (!empty($decoded['mediaType'])) {
            $encoded = "{$decoded['mediaType']}";
        }

        foreach ($decoded as $key => $value) {
            if ($key == 'mediaType') {
                continue;
            }

            if (!empty($value)) {
                $encoded .= "; {$key}={$value}";
            }
        }

        return $encoded;
    }
}
