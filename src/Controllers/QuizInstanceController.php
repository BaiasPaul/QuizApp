<?php


namespace QuizApp\Controllers;


use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use QuizApp\Services\QuizInstanceServices;
use QuizApp\Services\UserServices;

class QuizInstanceController extends AbstractController
{

    private $quizInstanceServices;

    /**
     * UserController constructor.
     * @param RendererInterface $renderer
     * @param QuizInstanceServices $quizTemplateServices
     */
    public function __construct(RendererInterface $renderer, QuizInstanceServices $quizTemplateServices)
    {
        parent::__construct($renderer);
        $this->quizInstanceServices = $quizTemplateServices;
    }

    public function showCandidateQuizzes(Request $request, array $requestAttributes)
    {
        $arguments['currentPage'] = (int)$request->getParameter('page');
        $arguments['pages'] = $this->quizInstanceServices->getQuizzNumber($requestAttributes);
        $arguments['username'] = $this->quizInstanceServices->getName();

        $arguments['quizzes'] = $this->quizInstanceServices->getQuizzes($requestAttributes, $request->getParameter('page'));

        return $this->renderer->renderView("candidate-quiz-listing.phtml", $arguments);



//        $arguments['currentPage'] = (int)$request->getParameter('page');
//        $arguments['pages'] = $this->userServices->getUserNumber($requestAttributes);
//        $arguments['username'] = $this->userServices->getName();
//        $arguments['dropdown'] = $requestAttributes;
//        if (empty($requestAttributes)) {
//            $arguments['dropdown'] = '';
//        }
//        $arguments['users'] = $this->userServices->getUsers($requestAttributes, $request->getParameter('page'));
//
//        return $this->renderer->renderView("admin-users-listing.phtml", $arguments);
    }
}
