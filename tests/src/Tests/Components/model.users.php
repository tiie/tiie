<?php

use Topi\Components;
use Tests\Components\Users;

return array(
    'init' => function(\Topi\Components $components, array $params = array()) {
        return new \Tests\Components\Users($components->get('@email'));
    },
    'after' => function($component, \Topi\Components\Scope $components, array $params = array()) {
        $component->categories($components->get('model.users.categories'));
    },
);
