<?php

namespace QuizApp\Services;

use Framework\Contracts\SessionInterface;
use QuizApp\Entities\User;
use ReallyOrm\Test\Repository\RepositoryManager;

class UserServices extends AbstractServices
{

    public function saveUser(string $name, string $email, string $password, string $role)
    {
        $user = new User();
        $user->setName($name);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setRole($role);
        $filters = ['name' => $name, 'email' => $email, 'password' => $password, 'role' => $role];
        $result = $this->repoManager->getRepository(User::class)->findOneBy($filters);
        if (!$result) {
            $this->repoManager->register($user);
            $user->save();

            return $result;
        }
        $this->repoManager->register($result);
        $result->save();

        return $result;
    }

    public function getUsers()
    {
        $result = $this->repoManager->getRepository(User::class)->findBy([],[],0,5);

        return $result;
    }

    public function getName()
    {
        return $this->session->get('name');
    }

    public function editUser( $id, $name, $email, $password, $role){
        $user = $this->repoManager->getRepository(User::class)->find($id);
        $user->setName($name);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setRole($role);

        $this->repoManager->register($user);
        $user->save();
    }

    public function getParams(int $id)
    {
        $user = $this->repoManager->getRepository(User::class)->find($id);
        $params = ['id'=>$user->getId(),'name'=>$user->getName(),'email'=>$user->getEmail(),'password'=>$user->getPassword(),'role'=>$user->getRole()];

        return $params;
    }

}
