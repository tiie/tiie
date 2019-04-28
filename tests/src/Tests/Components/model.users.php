<?php

use Tiie\Components\Supervisor as Components;
use Tests\Components\Users;

return array(
    'init' => function(Components $components, array $params = array()) {
        return new \Tests\Components\Users($components->get('@email'));
    },
    'after' => function($component, \Tiie\Components\Scope $components, array $params = array()) {
        $component->categories($components->get('model.users.categories'));
    },
);
