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
        $this->questionInstanceService->saveAnswer($answer, $requestAttributes['id']);
        $questionNumber = $request->getParameter('question');
        $body = Stream::createFromString("");
        $response = new Response($body, '1.1', 301);

        return $response->withHeader('Location', '/candidate-quiz-page?question=' . ($questionNumber + 1));
    }

    public function back(Request $request, array $requestAttributes)
    {
        $questionNumber = $request->getParameter('question');
        $body = Stream::createFromString("");
        $response = new Response($body, '1.1', 301);

        return $response->withHeader('Location', '/candidate-quiz-page?question=' . ($questionNumber - 1));
    }

    public function save(Request $request, array $requestAttributes)
    {
        $answer = $request->getParameter('answer');
        $this->questionInstanceService->saveAnswer($answer, $requestAttributes['id']);
        $body = Stream::createFromString("");
        $response = new Response($body, '1.1', 301);

        return $response->withHeader('Location', '/candidate-results');
    }

    public function showResults()
    {
        return $this->renderer->renderView("candidate-results.phtml", [
            'username'=>$this->questionInstanceService->getName(),
            'questions'=>$this->questionInstanceService->getQuestionsAnswered()
        ]);
    }

    public function showSuccessPage()
    {
        return $this->renderer->renderView("quiz-success-page.phtml", [
            'username' => $this->questionInstanceService->getName()
        ]);
    }
}
