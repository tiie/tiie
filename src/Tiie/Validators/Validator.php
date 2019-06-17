<?php
namespace Tiie\Validators;

use Tiie\Validators\ValidatorInterface;
use Tiie\Messages\MessagesInterface;
use Tiie\Messages\Helper as MessagesHelper;

abstract class Validator implements ValidatorInterface
{
    private $messages;

    public function getDescription()
    {
        return null;
    }

    public function setMessages(MessagesInterface $messages) : void
    {
        $exploded = explode("\\", static::class);

        $this->messages = new MessagesHelper($messages, array(
            'prefix' => sprintf("@Validators.%s", $exploded[count($exploded)-1]),
        ));
    }

    public function getMessages() : MessagesInterface
    {
        if (is_null($this->messages)) {
            // trigger_error(sprintf("There is not messages set for validator %s.", self::CLASS), E_USER_NOTICE);

            $this->messages = new class() implements MessagesInterface {
                public function get(string $code, array $params = array()): ?string
                {
                    return null;
                }
            };
        }

        return $this->messages;
    }

    public function setMessage(string $code, string $message, array $params = array()) : void
    {
        $this->messages->set($code, $message, $params);
    }
}

