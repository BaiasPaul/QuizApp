<?php

namespace QuizApp\Controller;

use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Message;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use Psr\Http\Message\MessageInterface;
use QuizApp\Entity\QuestionTemplate;
use QuizApp\Entity\QuizTemplate;
use QuizApp\Entity\User;
use QuizApp\Service\QuizTemplateService;
use QuizApp\Util\Paginator;

/**
 * Class QuizTemplateController
 * @package QuizApp\Controller
 */
class QuizTemplateController extends AbstractController
{
    /**
     * @var QuizTemplateService
     */
    private $quizTemplateService;
    /**
     *
     */
    const RESULTS_PER_PAGE = 5;

    /**
     * UserController constructor.
     * @param RendererInterface $renderer
     * @param QuizTemplateService $questionInstanceService
     */
    public function __construct(RendererInterface $renderer, QuizTemplateService $questionInstanceService)
    {
        parent::__construct($renderer);
        $this->quizTemplateService = $questionInstanceService;
    }

    /**
     * @param Request $request
     * @return Message|MessageInterface
     */
    public function createQuiz(Request $request): MessageInterface
    {
        //TODO modify after injecting the Session class in Controller
        $name = $request->getParameter('name');
        $description = $request->getParameter('description');
        $questions = $request->getParameter('questions');
        $currentUserId = $this->quizTemplateService->getId();
        $this->quizTemplateService->saveQuiz($name, $description, $questions, $currentUserId);
        $body = Stream::createFromString("");
        $response = new Response($body, '1.1', 301);

        return $response->withHeader('Location', '/admin-quizzes-listing');
    }

    /**
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function showQuizzes(Request $request, array $requestAttributes): Response
    {
        $quizName = $this->quizTemplateService->getFromParameter('quizName', $request, "");
        $userId = $this->quizTemplateService->getFromParameter('userId', $request, "");
        $currentPage = (int)$this->quizTemplateService->getFromParameter('page', $request, 1);
        $totalResults = (int)$this->quizTemplateService->getEntityNumberOfPagesByField(QuizTemplate::class, ['name' => $quizName, 'user_id' => $userId]);
        $quizzes = $this->quizTemplateService->getEntitiesByField(QuizTemplate::class, ['name' => $quizName, 'user_id' => $userId], $currentPage, self::RESULTS_PER_PAGE);
        $users = $this->quizTemplateService->getEntitiesByField(User::class, ['role' => 'Admin'], 1, 99999999);
        $paginator = new Paginator($totalResults, $currentPage, self::RESULTS_PER_PAGE);

        //TODO modify after injecting the Session class in Controller
        return $this->renderer->renderView("admin-quizzes-listing.phtml", [
            'quizName' => $quizName,
            'username' => $this->quizTemplateService->getName(),
            'users' => $users,
            'dropdownUserId' => $userId,
            'paginator' => $paginator,
            'quizzes' => $quizzes,
        ]);
    }

    /**
     * @param Request $request
     * @param array $requestAttributes
     * @return Message|MessageInterface
     */
    public function editQuiz(Request $request, array $requestAttributes): MessageInterface
    {
        $name = $request->getParameter('name');
        $description = $request->getParameter('description');
        $questions = $request->getParameter('questions');
        $this->quizTemplateService->editQuiz($requestAttributes['id'], $name, $description, $questions);
        $body = Stream::createFromString("");
        $response = new Response($body, '1.1', 301);

        return $response->withHeader('Location', '/admin-quizzes-listing');
    }

    /**
     * @param Request $request
     * @param array $requestAttributes
     * @return Message|MessageInterface
     */
    public function deleteQuiz(Request $request, array $requestAttributes): MessageInterface
    {
        $this->quizTemplateService->deleteQuiz($requestAttributes['id']);
        $body = Stream::createFromString("");
        $response = new Response($body, '1.1', 301);

        return $response->withHeader('Location', '/admin-quizzes-listing');
    }

    /**
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function showQuizDetailsEdit(Request $request, array $requestAttributes): Response
    {
        $params = $this->quizTemplateService->getParams($requestAttributes['id']);

        //TODO modify after injecting the Session class in Controller
        return $this->renderer->renderView("admin-quiz-details.phtml", [
            'id' => $params['id'],
            'name' => $params['name'],
            'description' => $params['description'],
            //TODO create class for these parameters
            'questions' => $quizzes = $this->quizTemplateService->getEntitiesByField(QuestionTemplate::class, [], 1, 99999999999),
            'selectedQuestions' => $this->quizTemplateService->getSelectedQuestions($requestAttributes['id']),
            'username' => $this->quizTemplateService->getName(),
            'path' => 'edit/' . $params['id'],
        ]);
    }

    /**
     * @return Response
     */
    public function showQuizDetails(): Response
    {
        //TODO modify after injecting the Session class in Controller
        return $this->renderer->renderView("admin-quiz-details.phtml", [
            'questions' => $quizzes = $this->quizTemplateService->getEntitiesByField(QuestionTemplate::class, [], 1, 99999999999),
            'username' => $this->quizTemplateService->getName(),
            'path' => 'create',
        ]);
    }
}
