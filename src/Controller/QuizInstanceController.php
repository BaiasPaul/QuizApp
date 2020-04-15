<?php


namespace QuizApp\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use Framework\Service\ParameterBag;
use Psr\Http\Message\MessageInterface;
use QuizApp\Entity\QuizInstance;
use QuizApp\Entity\QuizTemplate;
use QuizApp\Service\QuizInstanceService;
use QuizApp\Service\UserService;
use QuizApp\Util\Paginator;
use ReallyOrm\Filter;
use ReallyOrm\Test\Repository\RepositoryManager;

//TODO add comments

/**
 * Class QuizInstanceController
 * @package QuizApp\Controller
 */
class QuizInstanceController extends AbstractController
{

    /**
     * @var QuizInstanceService
     */
    private $quizInstanceService;

    /**
     * @var RepositoryManager
     */
    private $repoManager;

    /**
     * UserController constructor.
     * @param RendererInterface $renderer
     * @param QuizInstanceService $questionInstanceService
     * @param RepositoryManager $repoManager
     */
    public function __construct(
        RendererInterface $renderer,
        QuizInstanceService $questionInstanceService,
        RepositoryManager $repoManager
    ) {
        parent::__construct($renderer);
        $this->quizInstanceService = $questionInstanceService;
        $this->repoManager = $repoManager;
    }

    /**
     * Returns a Response with quizzes paginated
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return MessageInterface
     */
    public function showCandidateQuizzes(Request $request, array $requestAttributes): MessageInterface
    {
        //TODO modify after injecting the Session class in Controller
        $redirectToLogin = $this->verifySessionUserName($this->quizInstanceService->getSession());
        if ($redirectToLogin) {
            return $redirectToLogin;
        }

        $parameterBag = new ParameterBag([
            'orderBy' => $request->getParameter('orderBy', ''),
            'sort' => $request->getParameter('sort', ''),
            'results' => $request->getParameter('results', 5),
        ]);

        $resultsPerPage = $parameterBag->get('results');
        //TODO remove cast and modify methods to return the expected type
        $currentPage = (int)$this->quizInstanceService->getFromParameter('page', $request, 1);
        $totalResults = (int)$this->quizInstanceService->getEntityNumberOfPagesByField(QuizTemplate::class, []);

        $filtersForEntity = new Filter(
            [],
            $resultsPerPage,
            ($currentPage - 1) * $resultsPerPage,
            $parameterBag->get('orderBy'),
            $parameterBag->get('sort')
        );

        $quizzes = $this->repoManager->getRepository(QuizTemplate::class)->getFilteredEntities($filtersForEntity);
        $paginator = new Paginator($totalResults, $currentPage, $resultsPerPage);

        return $this->renderer->renderView("candidate-quiz-listing.phtml", [
            'username' => $this->quizInstanceService->getName(),
            'paginator' => $paginator,
            'quizzes' => $quizzes,
            'parameterBag' => $parameterBag,
        ]);
    }

    /**
     * Redirect to the Candidate quiz page after creating an instance of a quiz
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return MessageInterface
     */
    public function showCandidateQuizListing(Request $request, array $requestAttributes): MessageInterface
    {
        $this->quizInstanceService->setQuizId($requestAttributes['id']);
        $this->quizInstanceService->createQuizInstance();
        $body = Stream::createFromString("");
        $response = new Response($body, '1.1', 301);

        return $response->withHeader('Location', '/candidate-quiz-page?question=1');
    }

    /**
     * @param Request $request
     * @param array $requestAttributes
     * @return MessageInterface
     */
    public function showQuestions(Request $request, array $requestAttributes): MessageInterface
    {
        //TODO modify after injecting the Session class in Controller
        //TODO move params in an array for renderView
        $questionNumber = $request->getParameter('question');
        $question = $this->quizInstanceService->getQuestion($questionNumber - 1);
        $arguments['question'] = $question;
        $arguments['username'] = $this->quizInstanceService->getName();
        $arguments['currentQuestion'] = $questionNumber;
        $questionInstanceNumber = $question->getId();
        $arguments['questionNumber'] = $questionInstanceNumber;
        $maxQuestions = $this->quizInstanceService->getMaxQuestions();
        $arguments['maxQuestions'] = $maxQuestions;

        return $this->renderer->renderView("candidate-quiz-page.phtml", $arguments);
    }
}
