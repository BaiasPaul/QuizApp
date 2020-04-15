<?php

namespace QuizApp\Controller;

use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Message;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use Framework\Service\ParameterBag;
use Framework\Service\UrlBuilder;
use Psr\Http\Message\MessageInterface;
use QuizApp\Entity\QuestionTemplate;
use QuizApp\Entity\QuizTemplate;
use QuizApp\Entity\User;
use QuizApp\Service\QuizTemplateService;
use QuizApp\Util\Paginator;
use ReallyOrm\Filter;
use ReallyOrm\Test\Repository\RepositoryManager;

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
     * @var RepositoryManager
     */
    private $repositoryManager;

    /**
     * UserController constructor.
     * @param RendererInterface $renderer
     * @param QuizTemplateService $questionInstanceService
     * @param RepositoryManager $repositoryManager
     */
    public function __construct
    (
        RendererInterface $renderer,
        QuizTemplateService $questionInstanceService,
        RepositoryManager $repositoryManager
    ) {
        parent::__construct($renderer);
        $this->quizTemplateService = $questionInstanceService;
        $this->repositoryManager = $repositoryManager;
    }

    /**
     * Redirects to the Admin quizzes listing after creating a quiz
     *
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
     * Return a Response with all questions paginated
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function showQuizzes(Request $request, array $requestAttributes): Response
    {

        $parameterBag = new ParameterBag([
            'orderBy' => $request->getParameter('orderBy', ''),
            'sort' => $request->getParameter('sort', ''),
            'userId' => $request->getParameter('userId', ''),
            'name' => $request->getParameter('name', ''),
            'results' => $request->getParameter('results', 5),
        ]);

        $filters = [
            'name' => $parameterBag->get('name'),
            'user_id' => $parameterBag->get('userId')
        ];

        $resultsPerPage = $parameterBag->get('results');
        //TODO remove casts
        $currentPage = (int)$this->quizTemplateService->getFromParameter('page', $request, 1);
        //TODO modify this method
        $totalResults = (int)$this->quizTemplateService->getEntityNumberOfPagesByField(QuizTemplate::class, $filters);

        $filtersForEntity = new Filter(
            $filters,
            $resultsPerPage,
            ($currentPage - 1) * $resultsPerPage,
            $parameterBag->get('orderBy'),
            $parameterBag->get('sort')
        );

        $quizzes = $this->repositoryManager->getRepository(QuizTemplate::class)->getFilteredEntities($filtersForEntity);
        $users = $this->repositoryManager->getRepository(User::class)->getFilteredEntities(
            new Filter(['role' => 'Admin'])
        );

        $paginator = new Paginator($totalResults, $currentPage, $resultsPerPage);

        //TODO modify after injecting the Session class in Controller
        return $this->renderer->renderView("admin-quizzes-listing.phtml", [
            'username' => $this->quizTemplateService->getName(),
            'users' => $users,
            'quizzes' => $quizzes,
            'paginator' => $paginator,
            'parameterBag' => $parameterBag,
        ]);
    }

    /**
     * Redirects to the Admin quizzes listing after editing a quiz
     *
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
     * Redirects to the Admin quizzes listing after deleting a quiz
     *
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
     * Return a Response with a pre-filled edit form
     *
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
            'questions' => $quizzes = $this->repositoryManager->getRepository(QuestionTemplate::class)->getFilteredEntities(new Filter()),
            'selectedQuestions' => $this->quizTemplateService->getSelectedQuestions($requestAttributes['id']),
            'username' => $this->quizTemplateService->getName(),
            'path' => 'edit/' . $params['id'],
        ]);
    }

    /**
     * Return a Response with a create form
     *
     * @return Response
     */
    public function showQuizDetails(): Response
    {
        //TODO modify after injecting the Session class in Controller
        return $this->renderer->renderView("admin-quiz-details.phtml", [
            'questions' => $quizzes = $this->repositoryManager->getRepository(QuestionTemplate::class)->getFilteredEntities(new Filter()),
            'username' => $this->quizTemplateService->getName(),
            'path' => 'create',
        ]);
    }
}
