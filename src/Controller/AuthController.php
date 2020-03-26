<?php

namespace QuizApp\Controller;

use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Message;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use Psr\Http\Message\MessageInterface;
use QuizApp\Exception\UserNotFoundException;
use QuizApp\Service\AppMessageManager;
use QuizApp\Service\AuthService;

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
     * @return Message|MessageInterface
     */
    public function login(Request $request)
    {
        $body = Stream::createFromString("");
        $response = new Response($body, '1.1', 301);

        $email = $request->getParameter('email');
        $password = $request->getParameter('password');
        try {
            //checks if a user with the specified email and password exists
            $user = $this->authService->authenticate($email, $password);
        } catch (UserNotFoundException $e) {
            //the messageManager holds all the errorMessages that will be displayed in the view
            $this->messageManager->addErrorMessage($e->getMessage());

            return $response->withHeader('Location', "/");
        }

        return $response->withHeader('Location', "/". strtolower($user->getRole()));
    }

    /**
     * @return Message|MessageInterface
     */
    public function logout()
    {
        $this->authService->logout();
        $body = Stream::createFromString("");
        $response = new Response($body, '1.1', '301');

        return $response->withHeader('Location', '/');
    }

    /**
     * @return Response
     */
    public function showAdminDashboard()
    {
        return $this->renderer->renderView("admin-dashboard.phtml", ['username' => $this->authService->getName()]);
    }

}
