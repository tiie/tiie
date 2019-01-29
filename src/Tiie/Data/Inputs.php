<?php
namespace Tiie\Data;

use Tiie\Messages\MessagesInterface;
use Tiie\Data\Input;

class Inputs
{
    private $messages;

    function __construct(MessagesInterface $messages)
    {
        $this->messages = $messages;
    }

    public function create(array $input = array(), array $rules = array()) : Input
    {
        $input = new Input($input, $rules);

        $input->messages($this->messages);

        return $input;
    }
}

