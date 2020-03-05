<?php

use Framework\Renderer\Renderer;
use Framework\Routing\Router;

return array(
    'renderer' => [
        Renderer::CONFIG_KEY_BASE_VIEW_PATH => dirname(__DIR__) . '/views/'
    ],
    'dispatcher' => [
        'controllers_namespace' => 'QuizApp\Controllers',
        'controller_class_suffix' => 'Controller'
    ],
    'router' => [
        'routes' => [
            'list_users' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/user',
                Router::CONFIG_KEY_ACTION => 'read',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

            'view_user' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/user/{id}',
                Router::CONFIG_KEY_ACTION => 'getUser',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ATTRIBUTES => [
                    'id' => '\d+'
                ]
            ],

            'give_user_role_priority' => [
                Router::CONFIG_KEY_METHOD => 'POST',
                Router::CONFIG_KEY_PATH => '/user/{id}/role/{role}\?p={priority}',
                Router::CONFIG_KEY_ACTION => 'giveRolePriority',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ATTRIBUTES => [
                    'id' => '\d+',
                    'role' => 'ADMIN|GUEST',
                    'priority' => '\d+'
                ]
            ],

            'delete_user' => [
                Router::CONFIG_KEY_METHOD => 'DELETE',
                Router::CONFIG_KEY_PATH => '/user/{id}',
                Router::CONFIG_KEY_ACTION => 'deleteUser',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ATTRIBUTES => [
                    'id' => '\d+'
                ]
            ],
        ]
    ]


);
