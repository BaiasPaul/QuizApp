<?php


namespace QuizApp\Controllers;


use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use QuizApp\Services\QuestionTemplateServices;
use QuizApp\Services\QuizTemplateServices;

class QuestionTemplateController extends AbstractController
{

    private $questionTemplateServices;

    /**
     * UserController constructor.
     * @param RendererInterface $renderer
     * @param QuestionTemplateServices $questionInstanceServices
     */
    public function __construct(RendererInterface $renderer, QuestionTemplateServices $questionInstanceServices)
    {
        parent::__construct($renderer);
        $this->questionTemplateServices = $questionInstanceServices;
    }

    public function createQuestion(Request $request)
    {
        $text = $request->getParameter('text');
        $type = $request->getParameter('type');
        $answer = $request->getParameter('answer');
        $this->questionTemplateServices->saveQuestion($text, $type,$answer);
        $location = 'Location: http://quizApp.com/admin-questions-listing?page=1';
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }

    public function showQuestions(Request $request, array $requestAttributes)
    {
        $arguments['currentPage'] = (int)$request->getParameter('page');
        $arguments['pages'] = $this->questionTemplateServices->getQuestionNumber($requestAttributes);
        $arguments['username'] = $this->questionTemplateServices->getName();
        $arguments['questions'] = $this->questionTemplateServices->getQuestions($requestAttributes, $request->getParameter('page'));

        return $this->renderer->renderView("admin-questions-listing.phtml", $arguments);
    }

    public function editQuestion(Request $request, array $requestAttributes)
    {
        $text = $request->getParameter('text');
        $type = $request->getParameter('type');
        $answer = $request->getParameter('answer');
        $this->questionTemplateServices->editQuestion($requestAttributes['id'], $text, $type,$answer);

        $location = 'Location: http://quizApp.com/admin-questions-listing?page=1';
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }

    public function deleteQuestion(Request $request, array $requestAttributes)
    {
        $this->questionTemplateServices->deleteQuestion($requestAttributes['id']);

        $location = 'Location: http://quizApp.com/admin-questions-listing?page=1';
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }

    public function showQuestionDetailsEdit(Request $request, array $requestAttributes)
    {
        $params = $this->questionTemplateServices->getParams($requestAttributes['id']);
        $params['username'] = $this->questionTemplateServices->getName();
        $params['path'] = 'edit/' . $params['id'];

        return $this->renderer->renderView("admin-question-details.phtml", $params);
    }

    public function showQuestionDetails()
    {
        $params = $this->questionTemplateServices->getEmptyParams();
        $params['username'] = $this->questionTemplateServices->getName();
        $params['path'] = 'create';

        return $this->renderer->renderView("admin-question-details.phtml", $params);
    }

}
