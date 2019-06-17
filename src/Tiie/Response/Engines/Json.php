<?php
namespace Tiie\Response\Engines;

class Json implements \Tiie\Response\Engines\EngineInterface
{
    use \Tiie\Components\ComponentsTrait;

    public function prepare(\Tiie\Response\ResponseInterface $response, \Tiie\Http\Request $request, array $accept)
    {
        $headers = $response->getHeaders();
        $headers['Content-Type'] = 'application/json';

        $body = "";

        if (!is_null($response->getData())) {
            $body = json_encode($response->getData());
        }

        if ($body === false) {
            trigger_error(sprintf("An error occurred during JSON encoding '%s'.", json_last_error_msg()), E_USER_WARNING);

            $body = "";
        }

        if ($this->getComponents()->defined('@lang')) {
            $body = $this->getComponent("@lang")->translateText('pl', $body);
        }

        return array(
            'code' => $response->getCode(),
            'body' => $body,
            'headers' => $headers,
        );
    }
}
