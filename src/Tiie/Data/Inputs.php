<?php
namespace Tiie\Data;

use Tiie\Messages\MessagesInterface;

/**
 * @package Tiie\Data
 */
class Inputs
{
    /**
     * @var MessagesInterface
     */
    private $messages;

    /**
     * @param MessagesInterface $messages
     */
    function __construct(MessagesInterface $messages)
    {
        $this->messages = $messages;
    }

    /**
     * Create input instance.
     *
     * @param array $input
     * @param array $rules
     *
     * @return Input
     */
    public function create(array $input = array(), array $rules = array()) : Input
    {
        $input = new Input($input, $rules);

        $input->setMessages($this->messages);

        return $input;
    }
}

