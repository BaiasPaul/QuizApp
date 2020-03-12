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
     * @param UserServices $questionInstanceServices
     */
    public function __construct(RendererInterface $renderer, UserServices $questionInstanceServices)
    {
        parent::__construct($renderer);
        $this->userServices = $questionInstanceServices;
    }

    public function createUser(Request $request)
    {
        $name = $request->getParameter('name');
        $email = $request->getParameter('email');
        $password = md5($request->getParameter('password'));
        $role = $request->getParameter('role');
        $this->userServices->saveUser($name, $email, $password, $role);
        $location = 'Location: http://quizApp.com/admin-users-listing?page=1';
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }

    public function showUsers(Request $request, array $requestAttributes)
    {
        $arguments['currentPage'] = (int)$request->getParameter('page');
        $arguments['pages'] = $this->userServices->getUserNumber($requestAttributes);
        $arguments['username'] = $this->userServices->getName();
        $arguments['dropdown'] = $requestAttributes;
        if (empty($requestAttributes)) {
            $arguments['dropdown'] = '';
        }
        $arguments['users'] = $this->userServices->getUsers($requestAttributes, $request->getParameter('page'));

        return $this->renderer->renderView("admin-users-listing.phtml", $arguments);
    }

    public function showLogin()
    {
        return $this->renderer->renderView("login.html", []);
    }

    public function showUserDetails()
    {
        $params = $this->userServices->getEmptyParams();
        $params['username'] = $this->userServices->getName();
        $params['path'] = 'create';
        return $this->renderer->renderView("admin-user-details.phtml", $params);
    }

    public function showUserDetailsEdit(Request $request, array $requestAttributes)
    {
        $params = $this->userServices->getParams($requestAttributes['id']);
        $params['username'] = $this->userServices->getName();
        $params['path'] = 'edit/' . $params['id'];

        return $this->renderer->renderView("admin-user-details.phtml", $params);
    }

    public function editUser(Request $request, array $requestAttributes)
    {
        $name = $request->getParameter('name');
        $email = $request->getParameter('email');
        $password = $request->getParameter('password');
        $role = $request->getParameter('role');

        $this->userServices->editUser($requestAttributes['id'], $name, $email, $password, $role);

        $location = 'Location: http://quizApp.com/admin-users-listing?page=1';
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }

    public function deleteUser(Request $request, array $requestAttributes)
    {
        $this->userServices->deleteUser($requestAttributes['id']);

        $location = 'Location: http://quizApp.com/admin-users-listing?page=1';
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }

    public function showResults(Request $request, array $requestAttributes)
    {
        $arguments['currentPage'] = (int)$request->getParameter('page');
        $arguments['pages'] = $this->userServices->getUserNumber($requestAttributes);
        $arguments['username'] = $this->userServices->getName();
        $arguments['quizzes'] = $this->userServices->getQuestionsInfo($request->getParameter('page'));

        return $this->renderer->renderView("admin-results-listing.phtml", $arguments);
    }

}
