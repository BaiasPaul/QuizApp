<?php

namespace QuizApp\Controllers;

use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use QuizApp\Services\QuestionTemplateService;
use QuizApp\Services\QuizTemplateService;
use QuizApp\Util\Paginator;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;

/**
 * Class QuestionTemplateController
 * @package QuizApp\Controllers
 */
class QuestionTemplateController extends AbstractController
{

    /**
     * @var QuestionTemplateService
     */
    private $questionTemplateServices;

    /**
     * @var Paginator
     */
    private $paginator;

    /**
     * UserController constructor.
     * @param RendererInterface $renderer
     * @param QuestionTemplateService $questionInstanceServices
     * @param Paginator $paginator
     */
    public function __construct(RendererInterface $renderer, QuestionTemplateService $questionInstanceServices, Paginator $paginator)
    {
        parent::__construct($renderer);
        $this->questionTemplateServices = $questionInstanceServices;
        $this->paginator = $paginator;
    }

    /**
     * This method creates a question and saves it in the database
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
        $location = 'Location: http://quizApp.com/admin-questions-listing';
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }

    /**
     * This method returns a Response with the questions searched by getQuestionsByText with the specified page and text
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function showQuestions(Request $request, array $requestAttributes)
    {
        $text = $this->questionTemplateServices->getFromParameter('text',$request,"");
        $currentPage = (int)$this->questionTemplateServices->getFromParameter('page',$request,1);
        $totalResults = (int)$this->questionTemplateServices->getQuestionNumberOfPagesByText($text);
        $resultsPerPage = 5;

        $this->paginator->setCurrentPage($currentPage);
        $this->paginator->setTotalResults($totalResults);
        $this->paginator->setTotalPages($totalResults, $resultsPerPage);

        $arguments['username'] = $this->questionTemplateServices->getName();
        $arguments['text'] = $text;
        $arguments['questions'] = $this->questionTemplateServices->getQuestionsByText($text, $currentPage, $resultsPerPage);
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

        $location = 'Location: http://quizApp.com/admin-questions-listing';
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }

    /**
     * This method deletes a question and redirects to /admin-questions-listing
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function deleteQuestion(Request $request, array $requestAttributes)
    {
        $this->questionTemplateServices->deleteQuestion($requestAttributes['id']);

        $location = 'Location: http://quizApp.com/admin-questions-listing';
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }

    /**
     * This method displays a pre-filled 'Edit Question' form
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
     * This method displays a 'Create Question' form
     *
     * @return Response
     */
    public function showQuestionDetails()
    {
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
    public function prepareTextForSearch(Request $request, array $requestAttributes)
    {
        $text = $request->getParameter('text');

        $location = "Location: http://quizApp.com/admin-questions-listing?text=$text";
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }
}
