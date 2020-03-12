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

        $location = 'Location: http://quizApp.com/candidate-quiz-page?question='.$requestAttributes['question'];
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }
}
