<?php


namespace QuizApp\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use QuizApp\Service\QuestionInstanceService;
use QuizApp\Service\QuestionTemplateService;

class QuestionInstanceController extends AbstractController
{
    private $questionInstanceService;

    /**
     * UserController constructor.
     * @param RendererInterface $renderer
     * @param QuestionInstanceService $questionInstanceService
     */
    public function __construct(RendererInterface $renderer, QuestionInstanceService $questionInstanceService)
    {
        parent::__construct($renderer);
        $this->questionInstanceService = $questionInstanceService;
    }

    public function saveAnswer(Request $request, array $requestAttributes)
    {
        $answer = $request->getParameter('answer');
        $this->questionInstanceService->saveAnswer($answer,$requestAttributes['id']);
        $questionNumber = $request->getParameter('question');
        $location = 'Location: http://quizApp.com/candidate-quiz-page?question='.($questionNumber+1);
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }

    public function back(Request $request, array $requestAttributes)
    {
        $questionNumber = $request->getParameter('question');
        $location = 'Location: http://quizApp.com/candidate-quiz-page?question='.($questionNumber-1);
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }

    public function save(Request $request, array $requestAttributes)
    {
        $answer = $request->getParameter('answer');
        $this->questionInstanceService->saveAnswer($answer,$requestAttributes['id']);
        $location = 'Location: http://quizApp.com/candidate-results';
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }

    public function showResults(){
        $arguments['username'] = $this->questionInstanceService->getName();
        $questions = $this->questionInstanceService->getQuestionsAnswered();
        $arguments['questions'] = $questions;

        return $this->renderer->renderView("candidate-results.phtml", $arguments);
    }

    public function showSuccessPage(){
        $arguments['username'] = $this->questionInstanceService->getName();

        return $this->renderer->renderView("quiz-success-page.phtml", $arguments);
    }
}