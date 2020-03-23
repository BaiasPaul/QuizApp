<?php

namespace QuizApp\Controller;

use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use QuizApp\Entity\QuestionTemplate;
use QuizApp\Service\QuestionTemplateService;
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
    private $questionTemplateService;

    /**
     * UserController constructor.
     * @param RendererInterface $renderer
     * @param QuestionTemplateService $questionInstanceService
     */
    public function __construct(RendererInterface $renderer, QuestionTemplateService $questionInstanceService)
    {
        parent::__construct($renderer);
        $this->questionTemplateService = $questionInstanceService;
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
        $this->questionTemplateService->saveQuestion($text, $type, $answer);
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
        $resultsPerPage = 5;
        $text = $this->questionTemplateService->getFromParameter('text', $request, "");
        $type = $this->questionTemplateService->getFromParameter('type', $request, "");
        $currentPage = (int)$this->questionTemplateService->getFromParameter('page', $request, 1);
        $totalResults = (int)$this->questionTemplateService->getEntityNumberOfPagesByField(QuestionTemplate::class, ['type' => $type, 'text' => $text]);
        $questions = $this->questionTemplateService->getEntitiesByField(QuestionTemplate::class, ['type' => $type, 'text' => $text], $currentPage, $resultsPerPage);

        $paginator = new Paginator($totalResults, $currentPage, $resultsPerPage);
        $paginator->setTotalPages($totalResults, $resultsPerPage);

        return $this->renderer->renderView("admin-questions-listing.phtml", [
            'text' => $text,
            'username'=>$this->questionTemplateService->getName(),
            'dropdownType' => $type,
            'paginator' => $paginator,
            'questions' => $questions,
        ]);
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
        $this->questionTemplateService->editQuestion($requestAttributes['id'], $text, $type, $answer);

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
        $this->questionTemplateService->deleteQuestion($requestAttributes['id']);

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
        $params = $this->questionTemplateService->getParams($requestAttributes['id']);
        $params['username'] = $this->questionTemplateService->getName();
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
        $params['username'] = $this->questionTemplateService->getName();
        $params['path'] = 'create';

        return $this->renderer->renderView("admin-question-details.phtml", $params);
    }
}
