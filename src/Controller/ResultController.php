<?php


namespace QuizApp\Controller;

use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Message;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use Framework\Service\ParameterBag;
use Psr\Http\Message\MessageInterface;
use QuizApp\Entity\QuizInstance;
use QuizApp\Service\ResultService;
use QuizApp\Util\Paginator;
use ReallyOrm\Filter;
use ReallyOrm\Test\Repository\RepositoryManager;

/**
 * Class ResultController
 * @package QuizApp\Controller
 */
class ResultController extends AbstractController
{
    /**
     * @var ResultService
     */
    private $resultService;

    /**
     * @var RepositoryManager
     */
    private $repoManager;

    /**
     * UserController constructor.
     * @param RendererInterface $renderer
     * @param ResultService $resultService
     * @param RepositoryManager $repoManager
     */
    public function __construct(
        RendererInterface $renderer,
        ResultService $resultService,
        RepositoryManager $repoManager
    ) {
        parent::__construct($renderer);
        $this->resultService = $resultService;
        $this->repoManager = $repoManager;
    }

    /**
     * Returns a Response with paginated results
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Message|MessageInterface
     */
    public function showResults(Request $request, array $requestAttributes): MessageInterface
    {
        //TODO modify after injecting the Session class in Controller
        $redirectToLogin = $this->verifySessionUserName($this->resultService->getSession());
        if ($redirectToLogin) {
            return $redirectToLogin;
        }

        $parameterBag = new ParameterBag([
            'orderBy' => $request->getParameter('orderBy', ''),
            'sort' => $request->getParameter('sort', ''),

            'results' => $request->getParameter('results', 5),
        ]);


        $resultsPerPage = $parameterBag->get('results');
        //TODO remove casts
        $currentPage = (int)$this->resultService->getFromParameter('page', $request, 1);
        //TODO modify this method
        $totalResults = (int)$this->resultService->getEntityNumberOfPagesByField(QuizInstance::class, []);

        $filtersForEntity = new Filter(
            [],
            $resultsPerPage,
            ($currentPage - 1) * $resultsPerPage,
            $parameterBag->get('orderBy'),
            $parameterBag->get('sort')
        );
        $quizzes = $this->repoManager->getRepository(QuizInstance::class)->getFilteredEntities($filtersForEntity);

        $paginator = new Paginator($totalResults, $currentPage, $resultsPerPage);

        return $this->renderer->renderView("admin-results-listing.phtml", [
            'username' => $this->resultService->getName(),
            'paginator' => $paginator,
            'quizzes' => $quizzes,
            'parameterBag' => $parameterBag,
        ]);
    }

    /**
     * Returns a Response with all the questions answered
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function showQuizzesResults(Request $request, array $requestAttributes): Response
    {
        $questions = $this->resultService->getQuestionsAnswered($requestAttributes['id']);
        //TODO modify after injecting the Session class in Controller
        return $this->renderer->renderView("admin-results.phtml", [
            'username' => $this->resultService->getName(),
            'questions' => $questions,
            'quizId' => $requestAttributes['id']
        ]);
    }

    /**
     * Redirects to the Admin results listing page after setting a score to a quiz
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Message|MessageInterface
     */
    public function saveScore(Request $request, array $requestAttributes): MessageInterface
    {
        //TODO remove cast and fix method
        $score = (int)$this->resultService->getFromParameter('score', $request, 0);
        $this->resultService->setScore($score, $requestAttributes['id']);
        $body = Stream::createFromString("");
        $response = new Response($body, '1.1', 301);

        return $response->withHeader('Location', '/admin-results-listing');
    }
}
