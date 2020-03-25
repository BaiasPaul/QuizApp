<?php

use Framework\Renderer\Renderer;
use Framework\Routing\Router;

return array(
    'renderer' => [
        Renderer::CONFIG_KEY_BASE_VIEW_PATH => dirname(__DIR__) . '/views/'
    ],
    'dispatcher' => [
        'controllers_namespace' => 'QuizApp\Controller',
        'controller_class_suffix' => 'Controller'
    ],
    'router' => [
        'routes' => [
            'lading_page' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/',
                Router::CONFIG_KEY_ACTION => 'showLogin',
                Router::CONFIG_KEY_CONTROLLER => 'auth',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

            'login' => [
                Router::CONFIG_KEY_METHOD => 'POST',
                Router::CONFIG_KEY_PATH => '/login',
                Router::CONFIG_KEY_ACTION => 'login',
                Router::CONFIG_KEY_CONTROLLER => 'auth',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

            'admin_homepage' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/admin',
                Router::CONFIG_KEY_ACTION => 'showAdminDashboard',
                Router::CONFIG_KEY_CONTROLLER => 'auth',
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

            'show_question_details_create' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/admin-question-details/create',
                Router::CONFIG_KEY_ACTION => 'showQuestionDetails',
                Router::CONFIG_KEY_CONTROLLER => 'questionTemplate',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

            'save_question' => [
                Router::CONFIG_KEY_METHOD => 'POST',
                Router::CONFIG_KEY_PATH => '/admin-question-details/create',
                Router::CONFIG_KEY_ACTION => 'createQuestion',
                Router::CONFIG_KEY_CONTROLLER => 'questionTemplate',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

            'show_quiz_details_create' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/admin-quiz-details/create',
                Router::CONFIG_KEY_ACTION => 'showQuizDetails',
                Router::CONFIG_KEY_CONTROLLER => 'quizTemplate',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

            'save_quiz' => [
                Router::CONFIG_KEY_METHOD => 'POST',
                Router::CONFIG_KEY_PATH => '/admin-quiz-details/create',
                Router::CONFIG_KEY_ACTION => 'createQuiz',
                Router::CONFIG_KEY_CONTROLLER => 'quizTemplate',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

            'candidate_homepage' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/candidate',
                Router::CONFIG_KEY_ACTION => 'showCandidateQuizzes',
                Router::CONFIG_KEY_CONTROLLER => 'quizInstance',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

            'candidate_quiz_listing' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/candidate-quiz-page/{id}',
                Router::CONFIG_KEY_ACTION => 'showCandidateQuizListing',
                Router::CONFIG_KEY_CONTROLLER => 'quizInstance',
                Router::CONFIG_KEY_ATTRIBUTES => [
                    'id' => '\d+'
                ]
            ],

            'candidate_quizzes' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/candidate-quiz-page',
                Router::CONFIG_KEY_ACTION => 'showQuestions',
                Router::CONFIG_KEY_CONTROLLER => 'quizInstance',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

            'save_answer' => [
                Router::CONFIG_KEY_METHOD => 'POST',
                Router::CONFIG_KEY_PATH => '/candidate-quiz-page/saveAnswer/{id}',
                Router::CONFIG_KEY_ACTION => 'saveAnswer',
                Router::CONFIG_KEY_CONTROLLER => 'questionInstance',
                Router::CONFIG_KEY_ATTRIBUTES => [
                    'id' => '\d+'
                ]
            ],

            'back_answer' => [
                Router::CONFIG_KEY_METHOD => 'POST',
                Router::CONFIG_KEY_PATH => '/candidate-quiz-page/back/{id}',
                Router::CONFIG_KEY_ACTION => 'back',
                Router::CONFIG_KEY_CONTROLLER => 'questionInstance',
                Router::CONFIG_KEY_ATTRIBUTES => [
                    'id' => '\d+'
                ]
            ],

            'save_answers' => [
                Router::CONFIG_KEY_METHOD => 'POST',
                Router::CONFIG_KEY_PATH => '/candidate-quiz-page/save/{id}',
                Router::CONFIG_KEY_ACTION => 'save',
                Router::CONFIG_KEY_CONTROLLER => 'questionInstance',
                Router::CONFIG_KEY_ATTRIBUTES => [
                    'id' => '\d+'
                ]
            ],

            'candidate_results' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/candidate-results',
                Router::CONFIG_KEY_ACTION => 'showResults',
                Router::CONFIG_KEY_CONTROLLER => 'result',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

            'success_page' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/quiz-success-page',
                Router::CONFIG_KEY_ACTION => 'showSuccessPage',
                Router::CONFIG_KEY_CONTROLLER => 'questionInstance',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

            'results_page' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/admin-results/{id}',
                Router::CONFIG_KEY_ACTION => 'showQuizzesResults',
                Router::CONFIG_KEY_CONTROLLER => 'result',
                Router::CONFIG_KEY_ATTRIBUTES => [
                    'id' => '\d+'
                ]
            ],

            'show_results_details_create' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/admin-results-details/create',
                Router::CONFIG_KEY_ACTION => 'showResultDetails',
                Router::CONFIG_KEY_CONTROLLER => 'result',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

            'save_results' => [
                Router::CONFIG_KEY_METHOD => 'POST',
                Router::CONFIG_KEY_PATH => '/admin-results-details/create',
                Router::CONFIG_KEY_ACTION => 'createResults',
                Router::CONFIG_KEY_CONTROLLER => 'result',
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

            'show_question_details_edit' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/admin-question-details/edit/{id}',
                Router::CONFIG_KEY_ACTION => 'showQuestionDetailsEdit',
                Router::CONFIG_KEY_CONTROLLER => 'questionTemplate',
                Router::CONFIG_KEY_ATTRIBUTES => [
                    'id' => '\d+'
                ]
            ],

            'edit_question' => [
                Router::CONFIG_KEY_METHOD => 'POST',
                Router::CONFIG_KEY_PATH => '/admin-question-details/edit/{id}',
                Router::CONFIG_KEY_ACTION => 'editQuestion',
                Router::CONFIG_KEY_CONTROLLER => 'questionTemplate',
                Router::CONFIG_KEY_ATTRIBUTES => [
                    'id' => '\d+'
                ]
            ],

            'show_quiz_details_edit' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/admin-quiz-details/edit/{id}',
                Router::CONFIG_KEY_ACTION => 'showQuizDetailsEdit',
                Router::CONFIG_KEY_CONTROLLER => 'quizTemplate',
                Router::CONFIG_KEY_ATTRIBUTES => [
                    'id' => '\d+'
                ]
            ],

            'edit_quiz' => [
                Router::CONFIG_KEY_METHOD => 'POST',
                Router::CONFIG_KEY_PATH => '/admin-quiz-details/edit/{id}',
                Router::CONFIG_KEY_ACTION => 'editQuiz',
                Router::CONFIG_KEY_CONTROLLER => 'quizTemplate',
                Router::CONFIG_KEY_ATTRIBUTES => [
                    'id' => '\d+'
                ]
            ],

            'delete_user' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/admin-user-details/delete/{id}',
                Router::CONFIG_KEY_ACTION => 'deleteUser',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ATTRIBUTES => [
                    'id'=> '\d+'
                ]
            ],

            'delete_question' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/admin-question-details/delete/{id}',
                Router::CONFIG_KEY_ACTION => 'deleteQuestion',
                Router::CONFIG_KEY_CONTROLLER => 'questionTemplate',
                Router::CONFIG_KEY_ATTRIBUTES => [
                    'id'=> '\d+'
                ]
            ],

            'delete_quiz' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/admin-quiz-details/delete/{id}',
                Router::CONFIG_KEY_ACTION => 'deleteQuiz',
                Router::CONFIG_KEY_CONTROLLER => 'quizTemplate',
                Router::CONFIG_KEY_ATTRIBUTES => [
                    'id'=> '\d+'
                ]
            ],

            'show_users' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/admin-users-listing',
                Router::CONFIG_KEY_ACTION => 'showUsers',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

            'show_questions' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/admin-questions-listing',
                Router::CONFIG_KEY_ACTION => 'showQuestions',
                Router::CONFIG_KEY_CONTROLLER => 'questionTemplate',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

            'show_quizzes' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/admin-quizzes-listing',
                Router::CONFIG_KEY_ACTION => 'showQuizzes',
                Router::CONFIG_KEY_CONTROLLER => 'quizTemplate',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

            'show_results' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/admin-results-listing',
                Router::CONFIG_KEY_ACTION => 'showResults',
                Router::CONFIG_KEY_CONTROLLER => 'result',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

            'save_score' => [
                Router::CONFIG_KEY_METHOD => 'POST',
                Router::CONFIG_KEY_PATH => '/admin-results-listing',
                Router::CONFIG_KEY_ACTION => 'saveScore',
                Router::CONFIG_KEY_CONTROLLER => 'result',
                Router::CONFIG_KEY_ATTRIBUTES => []
            ],

            'admin_user_listing_candidate' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_PATH => '/admin-users-listing',
                Router::CONFIG_KEY_ACTION => 'showUsers',
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
