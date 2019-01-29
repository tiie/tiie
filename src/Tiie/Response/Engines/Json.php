<?php
namespace Tiie\Response\Engines;

class Json implements \Tiie\Response\Engines\EngineInterface
{
    use \Tiie\ComponentsTrait;

    public function prepare(\Tiie\Response\ResponseInterface $response, \Tiie\Http\Request $request, array $accept)
    {
        $headers = $response->headers();
        $headers['Content-Type'] = 'application/json';

        $body = "";

        // $tocode = array();
        // foreach($response->data() as $key => $row) {
        //     // unset($row['id']);
        //     // unset($row['name']);
        //     unset($row['parentId']);
        //     unset($row['icon']);
        //     unset($row['path']);
        //     $tocode[$key] = $row;

        //     $body = json_encode($tocode);

        //     if ($body === false) {
        //         // todo [debug] Debug to delete
        //         die(var_export($row, true));
        //     }
        // }

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
