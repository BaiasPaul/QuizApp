<?php


namespace QuizApp\Controllers;


use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use QuizApp\Services\QuestionInstanceServices;
use QuizApp\Services\QuestionTemplateServices;

class QuestionInstanceController extends AbstractController
{
    private $questionInstanceServices;

    /**
     * UserController constructor.
     * @param RendererInterface $renderer
     * @param QuestionInstanceServices $questionInstanceServices
     */
    public function __construct(RendererInterface $renderer, QuestionInstanceServices $questionInstanceServices)
    {
        parent::__construct($renderer);
        $this->questionInstanceServices = $questionInstanceServices;
    }

    public function saveAnswer(Request $request, array $requestAttributes)
    {
        $answer = $request->getParameter('answer');
        $this->questionInstanceServices->saveAnswer($answer,$requestAttributes['id']);
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
        $this->questionInstanceServices->saveAnswer($answer,$requestAttributes['id']);
        $location = 'Location: http://quizApp.com/quiz-success-page';
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }

    public function showResults(){
        $arguments['username'] = $this->questionInstanceServices->getName();
        $questions = $this->questionInstanceServices->getQuestionsAnswered();
        $arguments['questions'] = $questions;

        return $this->renderer->renderView("candidate-results.phtml", $arguments);
    }

    public function showSuccessPage(){
        $arguments['username'] = $this->questionInstanceServices->getName();

        return $this->renderer->renderView("quiz-success-page.phtml", $arguments);
    }
}
