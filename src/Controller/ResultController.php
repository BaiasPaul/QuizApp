<?php


namespace QuizApp\Controller;

use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Message;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use Psr\Http\Message\MessageInterface;
use QuizApp\Entity\QuizInstance;
use QuizApp\Service\ResultService;
use QuizApp\Util\Paginator;

/**
 * Class ResultController
 * @package QuizApp\Controller
 */
class ResultController extends AbstractController
{
    /**
     * @var
     */
    private $resultService;
    const RESULTS_PER_PAGE = 5;

    /**
     * UserController constructor.
     * @param RendererInterface $renderer
     * @param ResultService $resultService
     */
    public function __construct(RendererInterface $renderer, ResultService $resultService)
    {
        parent::__construct($renderer);
        $this->resultService = $resultService;
    }

    /**
     * Will be moved in another issue
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Message|MessageInterface
     */
    public function showResults(Request $request, array $requestAttributes)
    {
        $redirectToLogin = $this->verifySessionUserName($this->resultService->getSession());
        if ($redirectToLogin){
            return $redirectToLogin;
        }
//        $email = $this->resultService->getFromParameter('email', $request, "");
//        $role = $this->resultService->getFromParameter('role', $request, "");
        $currentPage = (int)$this->resultService->getFromParameter('page', $request, 1);
        $totalResults = (int)$this->resultService->getEntityNumberOfPagesByField(QuizInstance::class, []);
        $quizzes = $this->resultService->getEntitiesByField(QuizInstance::class, [], $currentPage, self::RESULTS_PER_PAGE);

        $paginator = new Paginator($totalResults, $currentPage, self::RESULTS_PER_PAGE);
        //to be removed after pr 009
        $paginator->setTotalPages($totalResults, self::RESULTS_PER_PAGE);

        return $this->renderer->renderView("admin-results-listing.phtml", [
//            'email' => $email,
            'username' => $this->resultService->getName(),
//            'dropdownRole' => $role,
            'paginator' => $paginator,
            'quizzes' => $quizzes,
        ]);
    }

    /**
     * Will be moved in another issue
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function showQuizzesResults(Request $request, array $requestAttributes)
    {
        $questions = $this->resultService->getQuestionsAnswered($requestAttributes['id']);

        return $this->renderer->renderView("admin-results.phtml", [
            'username'=>$this->resultService->getName(),
            'questions'=>$questions,
            'quizId'=>$requestAttributes['id']
        ]);
    }

    /**
     * Will be moved in another issue
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Message|MessageInterface
     */
    public function saveScore(Request $request, array $requestAttributes)
    {
        $score = (int)$this->resultService->getFromParameter('score', $request, 0);
        $currentPage = (int)$this->resultService->getFromParameter('page', $request, 1);
        $totalResults = (int)$this->resultService->getEntityNumberOfPagesByField(QuizInstance::class, []);
        $quizzes = $this->resultService->getEntitiesByField(QuizInstance::class, [], $currentPage, self::RESULTS_PER_PAGE);
        $paginator = new Paginator($totalResults, $currentPage, self::RESULTS_PER_PAGE);
        $this->resultService->setScore($score,$requestAttributes['id']);
        $body = Stream::createFromString("");
        $response = new Response($body, '1.1', 301);

        return $response->withHeader('Location', '/admin-results-listing');
//        return $this->renderer->renderView("admin-results-listing.phtml", [
//            'username' => $this->resultService->getName(),
//            'paginator' => $paginator,
//            'quizzes' => $quizzes,
//        ]);
    }
}