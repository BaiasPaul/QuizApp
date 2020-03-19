<?php

namespace QuizApp\Controllers;

use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use QuizApp\Exception\UserNotFoundException;
use QuizApp\Service\AuthService;
use QuizApp\Services\AppMessageManager;

/**
 * Class AuthController
 * @package QuizApp\Controllers
 */
class AuthController extends AbstractController
{
    /**
     * @var AuthService
     */
    private $authService;

    /**
     * @var AppMessageManager
     */
    private $messageManager;

    /**
     * UserController constructor.
     * @param RendererInterface $renderer
     * @param AuthService $authService
     * @param AppMessageManager $messageManager
     */
    public function __construct(RendererInterface $renderer, AuthService $authService, AppMessageManager $messageManager)
    {
        parent::__construct($renderer);
        $this->authService = $authService;
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
            $user = $this->authService->authenticate($email, $password);
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
        $this->authService->logout();
        $location = 'Location: http://quizApp.com/';
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }

    /**
     * @return Response
     */
    public function showAdminDashboard()
    {
        $name = ['username' => $this->authService->getName()];

        return $this->renderer->renderView("admin-dashboard.phtml", $name);
    }

}
