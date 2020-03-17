<?php

namespace QuizApp\Controllers;

use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use QuizApp\Exceptions\UserNotFoundException;
use QuizApp\Services\AuthServices;

class AuthController extends AbstractController
{
    private $authServices;

    /**
     * UserController constructor.
     * @param RendererInterface $renderer
     * @param AuthServices $authServices
     */
    public function __construct(RendererInterface $renderer, AuthServices $authServices)
    {
        parent::__construct($renderer);
        $this->authServices = $authServices;
    }

    public function showLogin()
    {
        $errorMessage = $this->authServices->getErrorMessage();
        $this->authServices->removeErrorMessage();
        return $this->renderer->renderView("login.phtml", ['errorMessage' => $errorMessage]);
    }

    public function login(Request $request)
    {
        $location = 'Location: http://quizApp.com/';
        $body = Stream::createFromString("");

        $email = $request->getParameter('email');
        $password = $request->getParameter('password');
        try {
            $user = $this->authServices->authenticate($email, $password);
        } catch (UserNotFoundException $e) {
            return new Response($body, '1.1', '301', $location);
        }
        $location .= strtolower($user->getRole());
        if ($user->getRole() === 'candidate'){
            $location .= '?page=1';
        }

        return new Response($body, '1.1', '301', $location);
    }

    public function logout()
    {
        $this->authServices->logout();
        $location = 'Location: http://quizApp.com/';
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }

    public function showAdminDashboard()
    {
        $name = ['username' => $this->authServices->getName()];

        return $this->renderer->renderView("admin-dashboard.phtml", $name);
    }

}
