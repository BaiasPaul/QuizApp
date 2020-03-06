<?php


namespace QuizApp\Controllers;

use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use QuizApp\Entities\User;
use QuizApp\Services\UserServices;

/**
 * Class UserController
 * @package QuizApp\Controllers
 */
class UserController extends AbstractController
{
    private $userServices;

    /**
     * UserController constructor.
     * @param RendererInterface $renderer
     * @param UserServices $userServices
     */
    public function __construct(RendererInterface $renderer,UserServices $userServices)
    {
        parent::__construct($renderer);
        $this->userServices = $userServices;
    }

    /**
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function showLogin(Request $request, array $requestAttributes)
    {

        return $this->renderer->renderView("login.html", $requestAttributes);
    }

//    public function login(Request $request, array $requestAttributes)
//    {
//        $email = $request->getParameter('email');
//        $password = $request->getParameter('password');
//        $user = $this->userServices->searchUser($email, $password);
//        if (!$user) {
//            return $this->renderer->renderView("exceptions-page.html", $requestAttributes);
//        }
//        if ($this->userServices->getUserRole($user) === "Admin") {
//            return $this->renderer->renderView("admin-dashboard.html", $requestAttributes);
//        }
//        return $this->renderer->renderView("candidate-quiz-listing.html", $requestAttributes);
//    }

    /**
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function showAdminDashboard(Request $request, array $requestAttributes)
    {

        return $this->renderer->renderView("admin-dashboard.html", $requestAttributes);
    }

    /**
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function showAdminQuestionDetails(Request $request, array $requestAttributes)
    {

        return $this->renderer->renderView("admin-question-details.html", $requestAttributes);
    }

    /**
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function showAdminQuestionListing(Request $request, array $requestAttributes)
    {

        return $this->renderer->renderView("admin-question-listing.html", $requestAttributes);
    }

    /**
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function showAdminQuizDetails(Request $request, array $requestAttributes)
    {

        return $this->renderer->renderView("admin-quiz-details.html", $requestAttributes);
    }

    /**
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function showAdminQuizzesListing(Request $request, array $requestAttributes)
    {

        return $this->renderer->renderView("admin-quizzes-listing.html", $requestAttributes);
    }

    /**
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function showAdminResults(Request $request, array $requestAttributes)
    {

        return $this->renderer->renderView("admin-results.html", $requestAttributes);
    }

    /**
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function showAdminResultsListing(Request $request, array $requestAttributes)
    {

        return $this->renderer->renderView("admin-results-listing.html", $requestAttributes);
    }

    /**
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function showAdminUserDetails(Request $request, array $requestAttributes)
    {

        return $this->renderer->renderView("admin-user-details.html", $requestAttributes);
    }

    /**
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function showAdminUserListing(Request $request, array $requestAttributes)
    {

        return $this->renderer->renderView("admin-user-listing.html", $requestAttributes);
    }

    /**
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function showCandidateQuizListing(Request $request, array $requestAttributes)
    {

        return $this->renderer->renderView("candidate-quiz-listing.html", $requestAttributes);
    }

    /**
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function showCandidateQuizPage(Request $request, array $requestAttributes)
    {

        return $this->renderer->renderView("candidate-quiz-page.html", $requestAttributes);
    }

    /**
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function showExceptionsPage(Request $request, array $requestAttributes)
    {

        return $this->renderer->renderView("exceptions-page.html", $requestAttributes);
    }

    /**
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function showQuizSuccessPage(Request $request, array $requestAttributes)
    {

        return $this->renderer->renderView("quiz-success-page.html", $requestAttributes);
    }

    public function DELETE_ME()
    {
        $gheorghe = new User();
    }
}
