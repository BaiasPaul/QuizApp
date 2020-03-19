<?php


namespace QuizApp\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use QuizApp\Service\QuizInstanceService;
use QuizApp\Service\UserService;

class QuizInstanceController extends AbstractController
{

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
        $arguments['currentPage'] = (int)$request->getParameter('page');
        $arguments['pages'] = $this->quizInstanceService->getQuizNumber($requestAttributes);
        $arguments['username'] = $this->quizInstanceService->getName();
        $arguments['quizzes'] = $this->quizInstanceService->getQuizzes($requestAttributes, $request->getParameter('page'));

        return $this->renderer->renderView("candidate-quiz-listing.phtml", $arguments);
    }

    public function showCandidateQuizListing(Request $request, array $requestAttributes)
    {
        $this->quizInstanceService->setQuizId($requestAttributes['id']);
        $this->quizInstanceService->createQuizInstance();
        $location = 'Location: http://quizApp.com/candidate-quiz-page?question=1';
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
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
