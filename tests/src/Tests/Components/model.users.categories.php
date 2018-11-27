<?php

return array(
    'init' => function(\Elusim\Components $components, array $params = array()) {
        return new \Tests\Components\UsersCategories();
    },
    'after' => function($component, \Elusim\Components\Scope $components, array $params = array()) {

        $component->email($components->get('@email'));
        $component->users($components->get('model.users'));
    },
);
