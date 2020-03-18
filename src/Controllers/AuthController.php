<?php

namespace QuizApp\Controllers;

use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use QuizApp\Exceptions\UserNotFoundException;
use QuizApp\Services\AppMessageManager;
use QuizApp\Services\AuthServices;

/**
 * Class AuthController
 * @package QuizApp\Controllers
 */
class AuthController extends AbstractController
{
    /**
     * @var AuthServices
     */
    private $authServices;

    /**
     * @var AppMessageManager
     */
    private $messageManager;

    /**
     * UserController constructor.
     * @param RendererInterface $renderer
     * @param AuthServices $authServices
     * @param AppMessageManager $messageManager
     */
    public function __construct(RendererInterface $renderer, AuthServices $authServices, AppMessageManager $messageManager)
    {
        parent::__construct($renderer);
        $this->authServices = $authServices;
        $this->messageManager = $messageManager;
    }

    /**
     * The method returns a Response with a messageManager that has all errorMessages stored
     *
     * @return Response
     */
    public function showLogin()
    {
        return $this->renderer->renderView("login.phtml", ['messageManager' => $this->messageManager]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function login(Request $request)
    {
        $location = 'Location: http://quizApp.com/';
        $body = Stream::createFromString("");

        $email = $request->getParameter('email');
        $password = $request->getParameter('password');
        try {
            //checks if a user with the specified email and password exists
            $user = $this->authServices->authenticate($email, $password);
        } catch (UserNotFoundException $e) {
            //the messageManager holds all the errorMessages that will be displayed in the view
            $this->messageManager->addErrorMessage($e->getMessage());
            return new Response($body, '1.1', '301', $location);
        }
        $location .= strtolower($user->getRole());
        if ($user->getRole() === 'candidate'){
            $location .= '?page=1';
        }

        return new Response($body, '1.1', '301', $location);
    }

    /**
     * @return Response
     */
    public function logout()
    {
        $this->authServices->logout();
        $location = 'Location: http://quizApp.com/';
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }

    /**
     * @return Response
     */
    public function showAdminDashboard()
    {
        $name = ['username' => $this->authServices->getName()];

        return $this->renderer->renderView("admin-dashboard.phtml", $name);
    }

}
