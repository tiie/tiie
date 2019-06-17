<?php
namespace Tiie\Messages;

class Helper implements MessagesInterface
{
    /**
     * @var MessagesInterface
     */
    private $messages;

    /**
     * Local storage.
     *
     * @var array
     */
    private $local = array();

    /**
     * @var array
     */
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

    public function setParams(string $code, array $params = array()) : void
    {
        $this->local[$code] = array(
            "params" => $params,
        );
    }

    public function setMessage(string $code, string $message) : void
    {
        $this->local[$code] = array(
            "message" => $message,
        );
    }

    public function get(string $code, array $params = array()) : ?string
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
