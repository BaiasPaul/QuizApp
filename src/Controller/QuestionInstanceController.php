<?php

namespace QuizApp\Controller;

use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use Psr\Http\Message\MessageInterface;
use QuizApp\Service\QuestionInstanceService;
use QuizApp\Service\QuestionTemplateService;

/**
 * Class QuestionInstanceController
 * @package QuizApp\Controller
 */
class QuestionInstanceController extends AbstractController
{
    /**
     * @var QuestionInstanceService
     */
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

    /**
     * Redirect to the Candidate quiz page with the next question shown
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return MessageInterface
     */
    public function saveAnswer(Request $request, array $requestAttributes): MessageInterface
    {
        $answer = $request->getParameter('answer');
        $this->questionInstanceService->saveAnswer($answer, $requestAttributes['id']);
        $questionNumber = $request->getParameter('question');
        $body = Stream::createFromString("");
        $response = new Response($body, '1.1', 301);

        return $response->withHeader('Location', '/candidate-quiz-page?question=' . ($questionNumber + 1));
    }

    /**
     * Redirects to the Candidate quiz page with the previous question shown
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return MessageInterface
     */
    public function back(Request $request, array $requestAttributes): MessageInterface
    {
        $questionNumber = $request->getParameter('question');
        $body = Stream::createFromString("");
        $response = new Response($body, '1.1', 301);

        return $response->withHeader('Location', '/candidate-quiz-page?question=' . ($questionNumber - 1));
    }

    /**
     * redirects to the Candidate results page after saving the quiz
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return MessageInterface
     */
    public function save(Request $request, array $requestAttributes): MessageInterface
    {
        $answer = $request->getParameter('answer');
        $this->questionInstanceService->saveAnswer($answer, $requestAttributes['id']);
        $body = Stream::createFromString("");
        $response = new Response($body, '1.1', 301);

        return $response->withHeader('Location', '/candidate-results');
    }

    /**
     * @return Response
     */
    public function showResults(): Response
    {
        //TODO modify username after injecting the Session class in Controller
        return $this->renderer->renderView("candidate-results.phtml", [
            'username'=>$this->questionInstanceService->getName(),
            'questions'=>$this->questionInstanceService->getQuestionsAnswered()
        ]);
    }

    /**
     * @return Response
     */
    public function showSuccessPage(): Response
    {
        //TODO modify username after injecting the Session class in Controller
        return $this->renderer->renderView("quiz-success-page.phtml", [
            'username' => $this->questionInstanceService->getName()
        ]);
    }
}
