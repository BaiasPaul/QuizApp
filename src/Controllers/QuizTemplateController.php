<?php

namespace QuizApp\Controllers;

use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use QuizApp\Services\QuizTemplateServices;

class QuizTemplateController extends AbstractController
{
    private $quizTemplateServices;

    /**
     * UserController constructor.
     * @param RendererInterface $renderer
     * @param QuizTemplateServices $questionInstanceServices
     */
    public function __construct(RendererInterface $renderer, QuizTemplateServices $questionInstanceServices)
    {
        parent::__construct($renderer);
        $this->quizTemplateServices = $questionInstanceServices;
    }

    public function createQuiz(Request $request)
    {
        $name = $request->getParameter('name');
        $description = $request->getParameter('description');
        $questions = $request->getParameter('questions');
        $currentUserId = $this->quizTemplateServices->getId();
        $this->quizTemplateServices->saveQuiz($name, $description,$questions,$currentUserId);
        $location = 'Location: http://quizApp.com/admin-quizzes-listing?page=1';
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }

    public function showQuizzes(Request $request, array $requestAttributes)
    {
        $arguments['currentPage'] = (int)$request->getParameter('page');
        $arguments['pages'] = $this->quizTemplateServices->getQuizNumber($requestAttributes);
        $arguments['username'] = $this->quizTemplateServices->getName();
        $arguments['quizzes'] = $this->quizTemplateServices->getQuizzes($requestAttributes, $request->getParameter('page'));
//        $arguments['quizUser'] = $this->quizTemplateServices->getQuizUser();


        return $this->renderer->renderView("admin-quizzes-listing.phtml", $arguments);
    }

    public function editQuiz(Request $request, array $requestAttributes)
    {
        $name = $request->getParameter('name');
        $description = $request->getParameter('description');
        $questions = $request->getParameter('questions');
        $this->quizTemplateServices->editQuiz($requestAttributes['id'], $name, $description,$questions);
        $location = 'Location: http://quizApp.com/admin-quizzes-listing?page=1';
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }

    public function deleteQuiz(Request $request, array $requestAttributes)
    {
        $this->quizTemplateServices->deleteQuiz($requestAttributes['id']);

        $location = 'Location: http://quizApp.com/admin-quizzes-listing?page=1';
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }

    public function showQuizDetailsEdit(Request $request, array $requestAttributes)
    {
        $params = $this->quizTemplateServices->getParams($requestAttributes['id']);
        $params['selectedQuestions'] = $this->quizTemplateServices->getSelectedQuestions($requestAttributes['id']);
        $params['questions'] = $this->quizTemplateServices->getQuestions();
        $params['username'] = $this->quizTemplateServices->getName();
        $params['path'] = 'edit/' . $params['id'];

        return $this->renderer->renderView("admin-quiz-details.phtml", $params);
    }

    public function showQuizDetails()
    {
        $params = $this->quizTemplateServices->getEmptyParams();
        $params['selectedQuestions'] = [];
        $params['questions'] = $this->quizTemplateServices->getQuestions();
        $params['username'] = $this->quizTemplateServices->getName();
        $params['path'] = 'create';

        return $this->renderer->renderView("admin-quiz-details.phtml", $params);
    }
}
