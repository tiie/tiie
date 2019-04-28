<?php
namespace Tiie\Response\Engines;

class Json implements \Tiie\Response\Engines\EngineInterface
{
    use \Tiie\Components\ComponentsTrait;

    public function prepare(\Tiie\Response\ResponseInterface $response, \Tiie\Http\Request $request, array $accept)
    {
        $headers = $response->headers();
        $headers['Content-Type'] = 'application/json';

        $body = "";

        if (!is_null($response->data())) {
            $body = json_encode($response->data());
        }

        if ($body === false) {
            trigger_error(sprintf("An error occurred during JSON encoding '%s'.", json_last_error_msg()), E_USER_WARNING);

            $body = "";
        }

        if ($this->components()->defined('@lang')) {
            $body = $this->component("@lang")->translateText('pl', $body);
        }

        return array(
            'code' => $response->code(),
            'body' => $body,
            'headers' => $headers,
        );
    }
}
