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
//            'list_users' => [
//                Router::CONFIG_KEY_METHOD => 'GET',
//                Router::CONFIG_KEY_PATH => '/user',
//                Router::CONFIG_KEY_ACTION => 'read',
//                Router::CONFIG_KEY_CONTROLLER => 'user',
//                Router::CONFIG_KEY_ATTRIBUTES => []
//            ],
//
//            'view_user' => [
//                Router::CONFIG_KEY_METHOD => 'GET',
//                Router::CONFIG_KEY_PATH => '/user/{id}',
//                Router::CONFIG_KEY_ACTION => 'getUser',
//                Router::CONFIG_KEY_CONTROLLER => 'user',
//                Router::CONFIG_KEY_ATTRIBUTES => [
//                    'id' => '\d+'
//                ]
//            ],
//
//            'give_user_role_priority' => [
//                Router::CONFIG_KEY_METHOD => 'POST',
//                Router::CONFIG_KEY_PATH => '/user/{id}/role/{role}\?p={priority}',
//                Router::CONFIG_KEY_ACTION => 'giveRolePriority',
//                Router::CONFIG_KEY_CONTROLLER => 'user',
//                Router::CONFIG_KEY_ATTRIBUTES => [
//                    'id' => '\d+',
//                    'role' => 'ADMIN|GUEST',
//                    'priority' => '\d+'
//                ]
//            ],
//
//            'delete_user' => [
//                Router::CONFIG_KEY_METHOD => 'DELETE',
//                Router::CONFIG_KEY_PATH => '/user/{id}',
//                Router::CONFIG_KEY_ACTION => 'deleteUser',
//                Router::CONFIG_KEY_CONTROLLER => 'user',
//                Router::CONFIG_KEY_ATTRIBUTES => [
//                    'id' => '\d+'
//                ]
//            ],

            'lading_page' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/',
                Router::CONFIG_KEY_ACTION => 'showLogin',
                Router::CONFIG_KEY_CONTROLLER => 'security',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

            'login' => [
                Router::CONFIG_KEY_METHOD => 'POST',
                Router::CONFIG_KEY_PATH => '/login',
                Router::CONFIG_KEY_ACTION => 'login',
                Router::CONFIG_KEY_CONTROLLER => 'security',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

            'admin_homepage' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/admin',
                Router::CONFIG_KEY_ACTION => 'showAdminDashboard',
                Router::CONFIG_KEY_CONTROLLER => 'security',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

            'candidate_homepage' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/candidate',
                Router::CONFIG_KEY_ACTION => 'showCandidateQuizzes',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

            'show_user_details_create' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/admin-user-details/create',
                Router::CONFIG_KEY_ACTION => 'showUserDetails',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

            'save_user' => [
                Router::CONFIG_KEY_METHOD => 'POST',
                Router::CONFIG_KEY_PATH => '/admin-user-details/create',
                Router::CONFIG_KEY_ACTION => 'createUser',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

            'show_user_details_edit' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/admin-user-details/edit/{id}',
                Router::CONFIG_KEY_ACTION => 'showUserDetailsEdit',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ATTRIBUTES => [
                    'id' => '\d+'
                ]
            ],

            'edit_user' => [
                Router::CONFIG_KEY_METHOD => 'POST',
                Router::CONFIG_KEY_PATH => '/admin-user-details/edit/{id}',
                Router::CONFIG_KEY_ACTION => 'editUser',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ATTRIBUTES => [
                    'id' => '\d+'
                ]
            ],

            'delete_user' => [
                Router::CONFIG_KEY_METHOD => 'POST',
                Router::CONFIG_KEY_PATH => '/admin-user-details/delete',
                Router::CONFIG_KEY_ACTION => 'deleteUser',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

            'show_users' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/admin-users-listing',
                Router::CONFIG_KEY_ACTION => 'showUsers',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

            'admin_dashboard' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/admin-dashboard',
                Router::CONFIG_KEY_ACTION => 'showAdminDashboard',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

            'admin_question_details' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/admin-question-details',
                Router::CONFIG_KEY_ACTION => 'showAdminQuestionDetails',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

            'admin_question_listing' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/admin-questions-listing',
                Router::CONFIG_KEY_ACTION => 'showAdminQuestionListing',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

            'admin_quiz_details' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/admin-quiz-details',
                Router::CONFIG_KEY_ACTION => 'showAdminQuizDetails',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

            'admin_quizzes_listing' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/admin-quizzes-listing',
                Router::CONFIG_KEY_ACTION => 'showAdminQuizzesListing',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

            'admin_results' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/admin-results',
                Router::CONFIG_KEY_ACTION => 'showAdminResults',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

            'admin_results_listing' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/admin-results-listing',
                Router::CONFIG_KEY_ACTION => 'showAdminResultsListing',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

            'admin_user_details' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/admin-user-details',
                Router::CONFIG_KEY_ACTION => 'showAdminUserDetails',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

//            'admin_user_listing' => [
//                Router::CONFIG_KEY_METHOD => 'GET',
//                Router::CONFIG_KEY_PATH => '/admin-users-listing',
//                Router::CONFIG_KEY_ACTION => 'showAdminUserListing',
//                Router::CONFIG_KEY_CONTROLLER => 'user',
//                Router::CONFIG_KEY_ATTRIBUTES => []
//            ],

            'candidate_quiz_listing' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/candidate-quiz-listing',
                Router::CONFIG_KEY_ACTION => 'CandidateQuizListing',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

            'candidate_quiz_page' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/candidate-quiz-page',
                Router::CONFIG_KEY_ACTION => 'CandidateQuizPage',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

            'exceptions_page' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/exceptions-page',
                Router::CONFIG_KEY_ACTION => 'showExceptionsPage',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

            'quiz_success_page' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/quiz-success-page',
                Router::CONFIG_KEY_ACTION => 'showQuizSuccessPage',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],
        ]
    ]

);
