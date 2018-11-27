<?php

use Elusim\Components;
use Tests\Components\Users;

return array(
    'init' => function(\Elusim\Components $components, array $params = array()) {
        return new \Tests\Components\Users($components->get('@email'));
    },
    'after' => function($component, \Elusim\Components\Scope $components, array $params = array()) {
        $component->categories($components->get('model.users.categories'));
    },
);
