<?php


namespace QuizApp\Controller;

use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use QuizApp\Entity\User;
use QuizApp\Service\UserService;

/**
 * Class UserController
 * @package QuizApp\Controllers
 */
class UserController extends AbstractController
{
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

    public function showLogin()
    {
<<<<<<< HEAD:src/Controllers/UserController.php
        return $this->renderer->renderView("login.html", []);
=======
        $name = $request->getParameter('name');
        $email = $request->getParameter('email');
        $password = md5($request->getParameter('password'));
        $role = $request->getParameter('role');
        $this->userService->saveUser($name, $email, $password, $role);
        $location = 'Location: http://quizApp.com/admin-users-listing?page=1';
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
>>>>>>> 005-feature-search-bar:src/Controller/UserController.php
    }

    public function showUsers(Request $request, array $requestAttributes)
    {
        $arguments['currentPage'] = (int)$request->getParameter('page');
        $arguments['pages'] = $this->userService->getUserNumber($requestAttributes);
        $arguments['username'] = $this->userService->getName();
        $arguments['dropdown'] = $requestAttributes;
        if (empty($requestAttributes)) {
            $arguments['dropdown'] = '';
        }
        $arguments['users'] = $this->userService->getUsers($requestAttributes, $request->getParameter('page'));

        return $this->renderer->renderView("admin-users-listing.phtml", $arguments);
    }

    public function showUserDetails()
    {
        $params = $this->userService->getEmptyParams();
        $params['username'] = $this->userService->getName();
        $params['path'] = 'create';
        return $this->renderer->renderView("admin-user-details.phtml", $params);
    }

    public function createUser(Request $request)
    {
        $name = $request->getParameter('name');
        $email = $request->getParameter('email');
        $password = $request->getParameter('password');
        $role = $request->getParameter('role');
        $this->userServices->saveUser($name, $email, $password, $role);
        $location = 'Location: http://quizApp.com/admin-users-listing?page=1';
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }

    public function showUserDetailsEdit(Request $request, array $requestAttributes)
    {
        $params = $this->userService->getParams($requestAttributes['id']);
        $params['username'] = $this->userService->getName();
        $params['path'] = 'edit/' . $params['id'];

        return $this->renderer->renderView("admin-user-details.phtml", $params);
    }

    public function editUser(Request $request, array $requestAttributes)
    {
        $name = $request->getParameter('name');
        $email = $request->getParameter('email');
        $password = $request->getParameter('password');
        $role = $request->getParameter('role');
<<<<<<< HEAD:src/Controllers/UserController.php
        $this->userServices->editUser($requestAttributes['id'], $name, $email, $password, $role);
=======

        $this->userService->editUser($requestAttributes['id'], $name, $email, $password, $role);
>>>>>>> 005-feature-search-bar:src/Controller/UserController.php

        $location = 'Location: http://quizApp.com/admin-users-listing?page=1';
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }

    public function deleteUser(Request $request, array $requestAttributes)
    {
        $this->userService->deleteUser($requestAttributes['id']);

        $location = 'Location: http://quizApp.com/admin-users-listing?page=1';
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }

    public function showResults(Request $request, array $requestAttributes)
    {
        $arguments['currentPage'] = (int)$request->getParameter('page');
        $arguments['pages'] = $this->userService->getQuizzesNumber($requestAttributes);
        $arguments['username'] = $this->userService->getName();
        $arguments['quizzes'] = $this->userService->getQuestionsInfo($request->getParameter('page'));

        return $this->renderer->renderView("admin-results-listing.phtml", $arguments);
    }

    public function showQuizzesResults(Request $request, array $requestAttributes)
    {
        $arguments['username'] = $this->userService->getName();
        $questions = $this->userService->getQuestionsAnswered($requestAttributes['id']);
        $arguments['questions'] = $questions;

        return $this->renderer->renderView("admin-results.phtml", $arguments);
    }

    public function saveScore(Request $request, array $requestAttributes)
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
