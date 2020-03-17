<?php


namespace QuizApp\Services;

use QuizApp\Entities\User;
use QuizApp\Exceptions\UserNotFoundException;

class AuthServices extends AbstractServices
{
    public function authenticate(string $email, string $password)
    {
        $filters = ['email' => $email, 'password' => md5($password)];
        $user =  $this->repoManager->getRepository(User::class)->findOneBy($filters);
        if (!$user){
            $this->session->set('errorMessage', 'Email or Password incorrect !');
            throw new UserNotFoundException();
        }
        $this->session->set('id', $user->getId());
        $this->session->set('name', $user->getName());
        $this->session->set('email', $user->getEmail());
        $this->session->set('role', $user->getRole());

        return $user;
    }

    public function getErrorMessage(){
        return $this->session->get('errorMessage');
    }

    public function logout()
    {
        $this->session->destroy();
    }

    public function removeErrorMessage()
    {
        $this->session->set('errorMessage','');
    }

}
