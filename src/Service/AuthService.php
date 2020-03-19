<?php


namespace QuizApp\Service;

use QuizApp\Entity\User;
use QuizApp\Exception\UserNotFoundException;
use ReallyOrm\Entity\EntityInterface;

/**
 * Class AuthService
 * @package QuizApp\Service
 */
class AuthService extends AbstractService
{
    /**
     * @param string $email
     * @param string $password
     * @return EntityInterface|null
     * @throws UserNotFoundException
     */
    public function authenticate(string $email, string $password)
    {
        $filters = ['email' => $email, 'password' => md5($password)];
        $user =  $this->repoManager->getRepository(User::class)->findOneBy($filters);
        if (!$user){
            throw new UserNotFoundException('Email or Password incorrect !');
        }
        $this->session->set('id', $user->getId());
        $this->session->set('name', $user->getName());
        $this->session->set('email', $user->getEmail());
        $this->session->set('role', $user->getRole());

        return $user;
    }

    public function logout()
    {
        $this->session->destroy();
    }

}
