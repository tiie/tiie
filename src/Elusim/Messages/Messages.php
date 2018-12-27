<?php
namespace Elusim\Messages;

use Elusim\Messages\MessagesInterface;

class Messages implements MessagesInterface
{
    public function get(string $code, array $params = array()) : ?string
    {
        return null;
    }
}
