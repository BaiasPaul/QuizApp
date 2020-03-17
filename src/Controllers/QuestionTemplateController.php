<?php


namespace QuizApp\Controllers;


use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use QuizApp\Services\QuestionTemplateServices;
use QuizApp\Services\QuizTemplateServices;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;

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
        $this->questionTemplateServices->saveQuestion($text, $type, $answer);
        $location = 'Location: http://quizApp.com/admin-questions-listing?page=1';
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }

    public function showQuestions(Request $request, array $requestAttributes)
    {
        $text = $request->getParameter('text');

        $arguments['currentPage'] = (int)$request->getParameter('page');
        $arguments['username'] = $this->questionTemplateServices->getName();
        $arguments['text'] = $text;
        if ($text){
            $arguments['questions'] = $this->questionTemplateServices->getQuestionsBtText($text, $request->getParameter('page'));
            $arguments['pages'] = $this->questionTemplateServices->getQuestionNumberOfPagesByText($text);

            return $this->renderer->renderView("admin-questions-listing.phtml", $arguments);
        }
        $arguments['questions'] = $this->questionTemplateServices->getQuestions($requestAttributes, $request->getParameter('page'));
        $arguments['pages'] = $this->questionTemplateServices->getQuestionNumberOfPages($requestAttributes);

        return $this->renderer->renderView("admin-questions-listing.phtml", $arguments);
    }

    public function editQuestion(Request $request, array $requestAttributes)
    {
        $text = $request->getParameter('text');
        $type = $request->getParameter('type');
        $answer = $request->getParameter('answer');
        $this->questionTemplateServices->editQuestion($requestAttributes['id'], $text, $type, $answer);

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

    public function searchByText(Request $request, array $requestAttributes)
    {
        $text = $request->getParameter('text');

        $location = "Location: http://quizApp.com/admin-questions-listing?page=1&text=$text";
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }

}
