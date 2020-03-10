<?php

namespace QuizApp\Controllers;

use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use QuizApp\Services\SecurityServices;

class SecurityController extends AbstractController
{
    private $securityServices;

    /**
     * UserController constructor.
     * @param RendererInterface $renderer
     * @param SecurityServices $securityServices
     */
    public function __construct(RendererInterface $renderer, SecurityServices $securityServices)
    {
        parent::__construct($renderer);
        $this->securityServices = $securityServices;
    }

    public function logout()
    {
        $this->securityServices->logout();
        $location = 'Location: http://quizApp.com/';
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }

    public function showLogin()
    {
        return $this->renderer->renderView("login.html", []);
    }

    public function login(Request $request)
    {
        $email = $request->getParameter('email');
        $password = md5($request->getParameter('password'));
        $userRole = $this->securityServices->searchUser($email, $password);
        $location =  'Location: http://quizApp.com/'.$userRole;
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }

    public function showAdminDashboard()
    {
        $name = ['name'=> $this->securityServices->getName()];

        return $this->renderer->renderView("admin-dashboard.phtml", $name);
    }

}
