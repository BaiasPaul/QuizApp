<?php


namespace QuizApp\Controllers;


use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use QuizApp\Services\SecurityServices;

class SecurityController extends AbstractController
{
    private $securityServices;

    /**
     * UserController constructor.
     * @param $securityServices
     */
    public function __construct(RendererInterface $renderer, SecurityServices $securityServices)
    {
        parent::__construct($renderer);
        $this->securityServices = $securityServices;
    }

    public function showLogin(Request $request, array $requestAttributes)
    {
        return $this->renderer->renderView("login.html", $requestAttributes);
    }

    public function login(Request $request, array $requestAttributes)
    {
        $email = $request->getParameter('email');
        $password = $request->getParameter('password');
        $user = $this->securityServices->searchUser($email, $password);
        if (!$user) {
            return $this->renderer->renderView("exceptions-page.html", $requestAttributes);
        }
        if ($user->getRole() === "Admin") {
            return $this->renderer->renderView("admin-dashboard.html", $requestAttributes);
        }
        return $this->renderer->renderView("candidate-quiz-listing.html", $requestAttributes);
    }
}