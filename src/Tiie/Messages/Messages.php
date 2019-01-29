<?php
namespace Tiie\Messages;

use Tiie\Messages\MessagesInterface;

class Messages implements MessagesInterface
{
    public function get(string $code, array $params = array()) : ?string
    {
        return null;
    }
}
