<?php


use Tiie\Components\Supervisor as Components;

return array(
    'init' => function(Components $components, array $params = array()) {
        return new \Tests\Components\UsersCategories();
    },
    'after' => function($component, \Tiie\Components\Scope $components, array $params = array()) {

        $component->email($components->get('@email'));
        $component->users($components->get('model.users'));
    },
);
