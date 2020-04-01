<?php


namespace QuizApp\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use Psr\Http\Message\MessageInterface;
use QuizApp\Entity\QuizInstance;
use QuizApp\Entity\QuizTemplate;
use QuizApp\Service\QuizInstanceService;
use QuizApp\Service\UserService;
use QuizApp\Util\Paginator;

//TODO add comments

/**
 * Class QuizInstanceController
 * @package QuizApp\Controller
 */
class QuizInstanceController extends AbstractController
{

    const RESULTS_PER_PAGE = 5;
    /**
     * @var QuizInstanceService
     */
    private $quizInstanceService;

    /**
     * UserController constructor.
     * @param RendererInterface $renderer
     * @param QuizInstanceService $questionInstanceService
     */
    public function __construct(RendererInterface $renderer, QuizInstanceService $questionInstanceService)
    {
        parent::__construct($renderer);
        $this->quizInstanceService = $questionInstanceService;
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
        //TODO remove cast and modify methods to return the expected type
        $currentPage = (int)$this->quizInstanceService->getFromParameter('page', $request, 1);
        $totalResults = (int)$this->quizInstanceService->getEntityNumberOfPagesByField(QuizTemplate::class, []);
        $quizzes = $this->quizInstanceService->getEntitiesByField(QuizTemplate::class, [], $currentPage, self::RESULTS_PER_PAGE);
        $paginator = new Paginator($totalResults, $currentPage, self::RESULTS_PER_PAGE);

        return $this->renderer->renderView("candidate-quiz-listing.phtml", [
            'username' => $this->quizInstanceService->getName(),
            'paginator' => $paginator,
            'quizzes' => $quizzes,
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
