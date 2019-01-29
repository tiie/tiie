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

    /**
     * Setting the 'mediaType' value or reading it.
     *
     * @param string $mediaType
     * @return $this|string
     */
    public function mediaType(string $mediaType = null)
    {
        $decoded = $this->decode($this->value());

        if (func_num_args() == 0) {
            return empty($decoded['mediaType']) ? null : $decoded['mediaType'];
        } else {
            $decoded = array_merge($decoded, array('mediaType' => $mediaType));

            $this->value($this->encode($decoded));

            return $this;
        }
    }

    /**
     * Setting the 'charset' value or reading it.
     *
     * @param string $charset
     * @return $this|string
     */
    public function charset(string $charset = null)
    {
        $decoded = $this->decode($this->value());

        if (func_num_args() == 0) {
            return empty($decoded['charset']) ? null : $decoded['charset'];
        } else {
            $decoded = array_merge($decoded, array('charset' => $charset));

            $this->value($this->encode($decoded));

            return $this;
        }
    }

    /**
     * Setting the 'boundary' value or reading it.
     *
     * @param string $boundary
     * @return $this|string
     */
    public function boundary(string $boundary = null)
    {
        $decoded = $this->decode($this->value());

        if (func_num_args() == 0) {
            return empty($decoded['boundary']) ? null : $decoded['boundary'];
        } else {
            $decoded = array_merge($decoded, array('boundary' => $boundary));

            $this->value($this->encode($decoded));

            return $this;
        }
    }

    private function decode(string $encoded)
    {
        $decoded = array();

        foreach (explode(';', $this->value()) as $key => $value) {
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
