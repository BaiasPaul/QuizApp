<?php


namespace QuizApp\Controllers;


use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use QuizApp\Services\QuestionTemplateServices;
use QuizApp\Services\QuizTemplateServices;

class QuizTemplateController extends AbstractController
{
    private $quizTemplateServices;

    /**
     * UserController constructor.
     * @param RendererInterface $renderer
     * @param QuizTemplateServices $quizTemplateServices
     */
    public function __construct(RendererInterface $renderer, QuizTemplateServices $quizTemplateServices)
    {
        parent::__construct($renderer);
        $this->quizTemplateServices = $quizTemplateServices;
    }

    public function showAdminQuizzesListing()
    {
        $name['username'] = $this->quizTemplateServices->getName();

        return $this->renderer->renderView("admin-quizzes-listing.phtml", $name);
    }

    public function showAdminQuizDetails()
    {
        $params['username'] = $this->quizTemplateServices->getName();
        $params['path'] = 'create';

        return $this->renderer->renderView("admin-quiz-details.phtml", $params);
    }
}
