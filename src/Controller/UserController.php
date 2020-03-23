<?php


namespace QuizApp\Controller;

use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use QuizApp\Entity\User;
use QuizApp\Service\UserService;
use QuizApp\Util\Paginator;

/**
 * Class UserController
 * @package QuizApp\Controllers
 */
class UserController extends AbstractController
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * UserController constructor.
     * @param RendererInterface $renderer
     * @param UserService $questionInstanceService
     */
    public function __construct(RendererInterface $renderer, UserService $questionInstanceService)
    {
        parent::__construct($renderer);
        $this->userService = $questionInstanceService;
    }

    /**
     * @return Response
     */
    public function showLogin(): Response
    {
        return $this->renderer->renderView("login.html", []);
    }

    /**
     * This method returns a Response with the users searched with the specified page and text
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function showUsers(Request $request, array $requestAttributes): Response
    {
        $resultsPerPage = 5;
        $email = $this->userService->getFromParameter('email', $request, "");
        $role = $this->userService->getFromParameter('role', $request, "");
        $currentPage = (int)$this->userService->getFromParameter('page', $request, 1);
        $totalResults = (int)$this->userService->getEntityNumberOfPagesByField(User::class, ['email' => $email, 'role' => $role]);
        $users = $this->userService->getEntitiesByField(User::class, ['email' => $email, 'role' => $role], $currentPage, $resultsPerPage);

        $paginator = new Paginator($totalResults, $currentPage, $resultsPerPage);
        $paginator->setTotalPages($totalResults, $resultsPerPage);

        return $this->renderer->renderView("admin-users-listing.phtml", [
            'email' => $email,
            'username'=>$this->userService->getName(),
            'dropdownRole' => $role,
            'paginator' => $paginator,
            'users' => $users,
        ]);
    }

    /**
     * This method returns a Response with the page ready for a create
     *
     * @return Response
     */
    public function showUserDetails(): Response
    {
        $params['username'] = $this->userService->getName();
        $params['path'] = 'create';

        return $this->renderer->renderView("admin-user-details.phtml", $params);
    }

    /**
     * This method creates an user and saves it in the database
     *
     * @param Request $request
     * @return Response
     */
    public function createUser(Request $request): Response
    {
        $name = $request->getParameter('name');
        $email = $request->getParameter('email');
        $password = $request->getParameter('password');
        $role = $request->getParameter('role');
        $this->userService->saveUser($name, $email, $password, $role);
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', 'Location: http://quizApp.com/admin-users-listing');
    }

    /**
     * This method returns a Response with the page ready for an edit
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function showUserDetailsEdit(Request $request, array $requestAttributes): Response
    {
        $params = $this->userService->getParams($requestAttributes['id']);
        $params['username'] = $this->userService->getName();
        $params['path'] = 'edit/' . $params['id'];

        return $this->renderer->renderView("admin-user-details.phtml", $params);
    }

    /**
     * This method edits an user with the attributes from the form
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function editUser(Request $request, array $requestAttributes): Response
    {
        $name = $request->getParameter('name');
        $email = $request->getParameter('email');
        $password = $request->getParameter('password');
        $role = $request->getParameter('role');
        $this->userService->editUser($requestAttributes['id'], $name, $email, $password, $role);
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', 'Location: http://quizApp.com/admin-users-listing');
    }

    /**
     * This method deletes an user and redirects to /admin-users-listing
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function deleteUser(Request $request, array $requestAttributes): Response
    {
        $this->userService->deleteUser($requestAttributes['id']);
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', 'Location: http://quizApp.com/admin-users-listing');
    }

    public function showExceptionsPage()
    {
        return $this->renderer->renderView("exceptions-page.phtml", ['errorMessage'=>'Route not found!']);
    }

    /**
     * Will be moved in another issue
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function showResults(Request $request, array $requestAttributes): Response
    {
        $arguments['currentPage'] = (int)$request->getParameter('page');
        $arguments['pages'] = $this->userService->getQuizzesNumber($requestAttributes);
        $arguments['username'] = $this->userService->getName();
        $arguments['quizzes'] = $this->userService->getQuestionsInfo($request->getParameter('page'));

        return $this->renderer->renderView("admin-results-listing.phtml", $arguments);
    }

    /**
     * Will be moved in another issue
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function showQuizzesResults(Request $request, array $requestAttributes): Response
    {
        $arguments['username'] = $this->userService->getName();
        $questions = $this->userService->getQuestionsAnswered($requestAttributes['id']);
        $arguments['questions'] = $questions;

        return $this->renderer->renderView("admin-results.phtml", $arguments);
    }

    /**
     * Will be moved in another issue
     *
     * @param Request $request
     * @param array $requestAttributes
     * @return Response
     */
    public function saveScore(Request $request, array $requestAttributes): Response
    {
        $arguments['currentPage'] = (int)$request->getParameter('page');
        $arguments['pages'] = $this->userService->getQuizzesNumber($requestAttributes);
        $arguments['username'] = $this->userService->getName();
        $arguments['quizzes'] = $this->userService->getQuestionsInfo($request->getParameter('page'));
        $score = $request->getParameter('score');
        $this->userService->setScore($score);

        return $this->renderer->renderView("admin-results-listing.phtml", $arguments);
    }
}
