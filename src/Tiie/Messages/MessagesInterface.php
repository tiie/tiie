<?php
namespace Tiie\Messages;

interface MessagesInterface
{
    public function get(string $code, array $params = array()) : ?string;
}
