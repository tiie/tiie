<?php

use Tiie\Components;
use Tests\Components\Users;

return array(
    'init' => function(\Tiie\Components $components, array $params = array()) {
        return new \Tests\Components\Users($components->get('@email'));
    },
    'after' => function($component, \Tiie\Components\Scope $components, array $params = array()) {
        $component->categories($components->get('model.users.categories'));
    },
);
