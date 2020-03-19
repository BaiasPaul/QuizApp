<?php


namespace QuizApp\Service;

use QuizApp\Entity\User;

class SecurityService extends AbstractService
{
    public function searchUser(string $email, string $password)
    {
        $filters = ['email' => $email, 'password' => $password];
        $user = $this->repoManager->getRepository(User::class)->findOneBy($filters);
            if (!$user){
            return "";
        }
        $this->session->set('id', $user->getId());
        $this->session->set('name', $user->getName());
        $this->session->set('email', $user->getEmail());
        $this->session->set('role', $user->getRole());
        $this->session->get('name');

        return strtolower($user->getRole());
    }

    public function logout()
    {
        $this->session->destroy();
    }

}
