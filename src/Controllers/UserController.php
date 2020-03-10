<?php


namespace QuizApp\Controllers;

use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
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
    public function __construct(RendererInterface $renderer, UserServices $userServices)
    {
        parent::__construct($renderer);
        $this->userServices = $userServices;
    }

    public function createUser(Request $request, array $requestAttributes)
    {
        $name = $request->getParameter('name');
        $email = $request->getParameter('email');
        $password = md5($request->getParameter('password'));
        $role = $request->getParameter('role');
        $this->userServices->saveUser($name, $email, $password, $role);

        $location = 'Location: http://quizApp.com/admin-users-listing';
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }

    public function showUsers(Request $request)
    {
//        $role = $request->getParameter('role');
        $arguments = $this->userServices->getUsers();

        return $this->renderer->renderView("admin-users-listing.phtml", $arguments);
    }

    /**
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function showLogin()
    {
        return $this->renderer->renderView("login.html", []);
    }


    public function showCandidateQuizzes(Request $request, array $requestAttributes)
    {
        $name = ['name' => $this->userServices->getName()];

        return $this->renderer->renderView("candidate-quiz-listing.phtml", $name);
    }

    public function showUserDetails()
    {
        $params = ['path'=>'create'];
        return $this->renderer->renderView("admin-user-details.phtml", $params);
    }

    public function showUserDetailsEdit(Request $request, array $requestAttributes)
    {
        $params = $this->userServices->getParams($requestAttributes['id']);
        $params['path'] = 'edit/'.$params['id'];

        return $this->renderer->renderView("admin-user-details.phtml", $params);
    }

    public function editUser(Request $request, array $requestAttributes)
    {
        $name = $request->getParameter('name');
        $email = $request->getParameter('email');
        $password = md5($request->getParameter('password'));
        $role = $request->getParameter('role');

        $this->userServices->editUser($requestAttributes['id'],$name, $email, $password, $role);

        $location = 'Location: http://quizApp.com/admin-users-listing';
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }
//
//    /**
//     * @param Request $request
//     * @param array $requestAttributes
//     * @return Response
//     */
//    public function showAdminQuestionDetails(Request $request, array $requestAttributes)
//    {
//
//        return $this->renderer->renderView("admin-question-details.html", $requestAttributes);
//    }
//
//    /**
//     * @param Request $request
//     * @param array $requestAttributes
//     * @return Response
//     */
//    public function showAdminQuestionListing(Request $request, array $requestAttributes)
//    {
//
//        return $this->renderer->renderView("admin-questions-listing.html", $requestAttributes);
//    }
//
//    /**
//     * @param Request $request
//     * @param array $requestAttributes
//     * @return Response
//     */
//    public function showAdminQuizDetails(Request $request, array $requestAttributes)
//    {
//
//        return $this->renderer->renderView("admin-quiz-details.html", $requestAttributes);
//    }
//
//    /**
//     * @param Request $request
//     * @param array $requestAttributes
//     * @return Response
//     */
//    public function showAdminQuizzesListing(Request $request, array $requestAttributes)
//    {
//
//        return $this->renderer->renderView("admin-quizzes-listing.html", $requestAttributes);
//    }
//
//    /**
//     * @param Request $request
//     * @param array $requestAttributes
//     * @return Response
//     */
//    public function showAdminResults(Request $request, array $requestAttributes)
//    {
//
//        return $this->renderer->renderView("admin-results.html", $requestAttributes);
//    }
//
//    /**
//     * @param Request $request
//     * @param array $requestAttributes
//     * @return Response
//     */
//    public function showAdminResultsListing(Request $request, array $requestAttributes)
//    {
//
//        return $this->renderer->renderView("admin-results-listing.html", $requestAttributes);
//    }
//
//    /**
//     * @param Request $request
//     * @param array $requestAttributes
//     * @return Response
//     */
//    public function showAdminUserDetails(Request $request, array $requestAttributes)
//    {
//
//        return $this->renderer->renderView("admin-user-details.phtml", $requestAttributes);
//    }
//
//    /**
//     * @param Request $request
//     * @param array $requestAttributes
//     * @return Response
//     */
////    public function showAdminUserListing(Request $request, array $requestAttributes)
////    {
////
////        return $this->renderer->renderView("admin-users-listing.phtml", $requestAttributes);
////    }
//
//    /**
//     * @param Request $request
//     * @param array $requestAttributes
//     * @return Response
//     */
//    public function showCandidateQuizListing(Request $request, array $requestAttributes)
//    {
//
//        return $this->renderer->renderView("candidate-quiz-listing.phtml", $requestAttributes);
//    }
//
//    /**
//     * @param Request $request
//     * @param array $requestAttributes
//     * @return Response
//     */
//    public function showCandidateQuizPage(Request $request, array $requestAttributes)
//    {
//
//        return $this->renderer->renderView("candidate-quiz-page.html", $requestAttributes);
//    }
//
//    /**
//     * @param Request $request
//     * @param array $requestAttributes
//     * @return Response
//     */
//    public function showExceptionsPage(Request $request, array $requestAttributes)
//    {
//
//        return $this->renderer->renderView("exceptions-page.html", $requestAttributes);
//    }
//
//    /**
//     * @param Request $request
//     * @param array $requestAttributes
//     * @return Response
//     */
//    public function showQuizSuccessPage(Request $request, array $requestAttributes)
//    {
//
//        return $this->renderer->renderView("quiz-success-page.html", $requestAttributes);
//    }

}
