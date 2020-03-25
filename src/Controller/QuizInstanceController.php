<?php


namespace QuizApp\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use QuizApp\Entity\QuizInstance;
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
        $currentPage = (int)$this->quizInstanceService->getFromParameter('page', $request, 1);
        $totalResults = (int)$this->quizInstanceService->getEntityNumberOfPagesByField(QuizInstance::class, []);
        $quizzes = $this->quizInstanceService->getEntitiesByField(QuizInstance::class, [], $currentPage, self::RESULTS_PER_PAGE);

        $paginator = new Paginator($totalResults, $currentPage, self::RESULTS_PER_PAGE);
        //to be removed after pr 009
        $paginator->setTotalPages($totalResults, self::RESULTS_PER_PAGE);

        return $this->renderer->renderView("candidate-quiz-listing.phtml", [
//            'email' => $email,
            'username' => $this->quizInstanceService->getName(),
//            'dropdownRole' => $role,
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

        return $response->withHeader('Location', 'http://quizApp.com/candidate-quiz-page?question=1');
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
