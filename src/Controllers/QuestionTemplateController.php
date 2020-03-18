<?php

namespace QuizApp\Controllers;

use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use QuizApp\Services\Paginator;
use QuizApp\Services\QuestionTemplateServices;
use QuizApp\Services\QuizTemplateServices;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;

/**
 * Class QuestionTemplateController
 * @package QuizApp\Controllers
 */
class QuestionTemplateController extends AbstractController
{

    /**
     * @var QuestionTemplateServices
     */
    private $questionTemplateServices;

    /**
     * @var Paginator
     */
    private $paginator;

    /**
     * UserController constructor.
     * @param RendererInterface $renderer
     * @param QuestionTemplateServices $questionInstanceServices
     * @param Paginator $paginator
     */
    public function __construct(RendererInterface $renderer, QuestionTemplateServices $questionInstanceServices, Paginator $paginator)
    {
        parent::__construct($renderer);
        $this->questionTemplateServices = $questionInstanceServices;
        $this->paginator = $paginator;
    }

    /**
     * This method creates a user and saves it in the database
     *
     * @param Request $request
     * @return Response
     */
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

    /**
     * This method returns a Response with the questions from a specified page and text
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function showQuestions(Request $request, array $requestAttributes)
    {
        $text = $request->getParameter('text');
        $currentPage = (int)$request->getParameter('page');
        $totalResults = (int)$this->questionTemplateServices->getQuestionNumberOfPagesByText($text);

        $arguments['username'] = $this->questionTemplateServices->getName();
        $arguments['text'] = $text;
        $arguments['questions'] = $this->questionTemplateServices->getQuestionsByText($text, $request->getParameter('page'));

        //sets the currentPage, totalResults and totalPages to us them in the view from the paginator variable
        $this->paginator->setCurrentPage($currentPage);
        $this->paginator->setTotalResults($totalResults);
        $this->paginator->setTotalPages($totalResults, 5);
        $arguments['paginator'] = $this->paginator;

        return $this->renderer->renderView("admin-questions-listing.phtml", $arguments);
    }

    /**
     * This method edits a question with the attributes from the form
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
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

    /**
     * This method searches for a question and deletes it from the database
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function deleteQuestion(Request $request, array $requestAttributes)
    {
        $this->questionTemplateServices->deleteQuestion($requestAttributes['id']);

        $location = 'Location: http://quizApp.com/admin-questions-listing?page=1';
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }

    /**
     * This method returns a Response with the attributes of a question fro autocomplete them in the edit form
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function showQuestionDetailsEdit(Request $request, array $requestAttributes)
    {
        $params = $this->questionTemplateServices->getParams($requestAttributes['id']);
        $params['username'] = $this->questionTemplateServices->getName();
        $params['path'] = 'edit/' . $params['id'];

        return $this->renderer->renderView("admin-question-details.phtml", $params);
    }

    /**
     * Because i use the same page for edit and create questions, it needs attributes to fill the inputs
     * for create it does not need them so the function getEmptyParams() returns an array with empty string attributes
     *
     * @return Response
     */
    public function showQuestionDetails()
    {
        $params = $this->questionTemplateServices->getEmptyParams();
        $params['username'] = $this->questionTemplateServices->getName();
        $params['path'] = 'create';

        return $this->renderer->renderView("admin-question-details.phtml", $params);
    }

    /**
     * This method returns a Response with the text parameter found in the input from the Request
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function searchByText(Request $request, array $requestAttributes)
    {
        $text = $request->getParameter('text');

        $location = "Location: http://quizApp.com/admin-questions-listing?page=1&text=$text";
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }

}
