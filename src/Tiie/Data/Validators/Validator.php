<?php
namespace Tiie\Data\Validators;

use Tiie\Data\Validators\ValidatorInterface;
use Tiie\Messages\MessagesInterface;
use Tiie\Messages\Helper as MessagesHelper;

abstract class Validator implements ValidatorInterface
{
    private $messages;

    public function description()
    {
        return null;
    }

    public function messages(MessagesInterface $messages = null)
    {
        if (is_null($messages)) {
            return $this->messages;
        } else {
            $exploded = explode("\\", static::class);

            $this->messages = new MessagesHelper($messages, array(
                'prefix' => sprintf("@Validators.%s", $exploded[count($exploded)-1]),
            ));

            return $this;
        }
    }

    public function message(string $code, string $message, array $params = array()) : ValidatorInterface
    {
        $this->messages->set($code, $message, $params);

        return $this;
    }
}

