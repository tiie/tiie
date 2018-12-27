<?php
namespace Elusim\Messages;

use Elusim\Messages\MessagesInterface;

class Helper
{
    private $messages;
    private $local;
    private $params;

    function __construct(MessagesInterface $messages, array $params = array())
    {
        $this->messages = $messages;
        $this->params = $params;
    }

    public function set(string $code, string $message, array $params = array())
    {
        $this->local[$code] = array(
            "message" => $message,
            "params" => $params,
        );

        return $this;
    }

    public function params(string $code, array $params = array())
    {
        $this->local[$code] = array(
            "params" => $params,
        );

        return $this;
    }

    public function message(string $code, string $message)
    {
        $this->local[$code] = array(
            "message" => $message,
        );

        return $this;
    }

    public function get(string $code, array $params = array())
    {
        if (!empty($this->local[$code]['params'])) {
            $params = array_merge($this->local[$code]['params'], $params);
        }

        $message = null;

        if (empty($this->local[$code]['message'])) {
            if (!empty($this->params['prefix'])) {
                $message = sprintf("%s.%s", $this->params['prefix'], ucfirst($code));
            }
        } else {
            $message = $this->local[$code]['message'];
        }

        return $this->messages->get($message, $params);
    }
}
