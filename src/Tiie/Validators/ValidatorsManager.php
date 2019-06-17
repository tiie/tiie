<?php
namespace Tiie\Validators;

use Tiie\Validators\ValidatorInterface;
use Tiie\Messages\MessagesInterface;

class ValidatorsManager
{
    private $sources;
    private $messages;

    function __construct(array $sources = array(), MessagesInterface $messages = null)
    {
        $this->sources = $sources;
        $this->messages = $messages;
    }

    public function get(string $name) : ?ValidatorInterface
    {
        $found = null;

        foreach ($this->sources as $source) {
            if (!empty($source['namespace'])) {
                $class = "{$source['namespace']}\\{$name}";
            } else {
                $class = $name;
            }

            if (class_exists($class)) {
                $found = $class;
                break;
            }
        }

        if (is_null($found)) {
            trigger_error("No validator found '{$name}'.", E_USER_WARNING);

            return null;
        }

        $validator = new $found();

        if (!is_null($this->messages)) {
            $validator->setMessages($this->messages);
        }

        return $validator;
    }
}
