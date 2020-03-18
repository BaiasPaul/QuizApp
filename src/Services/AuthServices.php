<?php


namespace QuizApp\Services;

use QuizApp\Entities\User;
use QuizApp\Exceptions\UserNotFoundException;
use ReallyOrm\Entity\EntityInterface;

/**
 * Class AuthServices
 * @package QuizApp\Services
 */
class AuthServices extends AbstractServices
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
