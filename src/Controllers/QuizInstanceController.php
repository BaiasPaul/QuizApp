<?php


namespace QuizApp\Controllers;


use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use QuizApp\Services\QuizInstanceService;
use QuizApp\Services\UserService;

class QuizInstanceController extends AbstractController
{

    private $quizInstanceServices;

    /**
     * UserController constructor.
     * @param RendererInterface $renderer
     * @param QuizInstanceService $questionInstanceServices
     */
    public function __construct(RendererInterface $renderer, QuizInstanceService $questionInstanceServices)
    {
        parent::__construct($renderer);
        $this->quizInstanceServices = $questionInstanceServices;
    }

    public function showCandidateQuizzes(Request $request, array $requestAttributes)
    {
        $arguments['currentPage'] = (int)$request->getParameter('page');
        $arguments['pages'] = $this->quizInstanceServices->getQuizNumber($requestAttributes);
        $arguments['username'] = $this->quizInstanceServices->getName();
        $arguments['quizzes'] = $this->quizInstanceServices->getQuizzes($requestAttributes, $request->getParameter('page'));

        return $this->renderer->renderView("candidate-quiz-listing.phtml", $arguments);
    }

    public function showCandidateQuizListing(Request $request, array $requestAttributes)
    {
        $this->quizInstanceServices->setQuizId($requestAttributes['id']);
//        $this->quizInstanceServices->deleteOldAnswers();
        $this->quizInstanceServices->createQuizInstance();
        $location = 'Location: http://quizApp.com/candidate-quiz-page?question=1';
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }

    public function showQuestions(Request $request, array $requestAttributes){
        $questionNumber = $request->getParameter('question');
        $question = $this->quizInstanceServices->getQuestion($questionNumber-1);
        $arguments['question'] = $question;
        $arguments['username'] = $this->quizInstanceServices->getName();
        $arguments['currentQuestion'] = $questionNumber;
        $questionInstanceNumber = $question->getId();
        $arguments['questionNumber'] = $questionInstanceNumber;
        $maxQuestions = $this->quizInstanceServices->getMaxQuestions();
        $arguments['maxQuestions'] = $maxQuestions;

        return $this->renderer->renderView("candidate-quiz-page.phtml", $arguments);
    }
}
