<?php


namespace QuizApp\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use QuizApp\Entity\QuizInstance;
use QuizApp\Entity\QuizTemplate;
use QuizApp\Service\QuizInstanceService;
use QuizApp\Service\UserService;
use QuizApp\Util\Paginator;

class QuizInstanceController extends AbstractController
{

    const RESULTS_PER_PAGE = 5;
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

    public function showCandidateQuizzes(Request $request, array $requestAttributes)
    {
        $redirectToLogin = $this->verifySessionUserName($this->quizInstanceService->getSession());
        if ($redirectToLogin) {
            return $redirectToLogin;
        }
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

    public function showCandidateQuizListing(Request $request, array $requestAttributes)
    {
        $this->quizInstanceService->setQuizId($requestAttributes['id']);
        $this->quizInstanceService->createQuizInstance();
        $body = Stream::createFromString("");
        $response = new Response($body, '1.1', 301);

        return $response->withHeader('Location', '/candidate-quiz-page?question=1');
    }

    public function showQuestions(Request $request, array $requestAttributes){
        $questionNumber = $request->getParameter('question');
        $question = $this->quizInstanceService->getQuestion($questionNumber-1);
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
