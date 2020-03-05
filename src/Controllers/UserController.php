<?php


namespace QuizApp\Controllers;


use Framework\Controller\AbstractController;
use Framework\Http\Request;

class UserController extends AbstractController
{
    public function getUser(Request $request,array $requestAttributes){

        return $this->renderer->renderView("user.phtml",$requestAttributes);
    }
}