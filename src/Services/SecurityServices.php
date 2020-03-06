<?php


namespace QuizApp\Services;


use QuizApp\Entities\User;
use ReallyOrm\Entity\EntityInterface;
use ReallyOrm\Repository\RepositoryManagerInterface;
use ReallyOrm\Test\Repository\RepositoryManager;

class SecurityServices
{
    private $repoManager;

    public function __construct(RepositoryManagerInterface $repoManager)
    {
        $this->repoManager = $repoManager;
    }

    public function searchUser(string $email, string $password): EntityInterface
    {
        $filters = ['email' => $email, 'password' => $password];

        return $this->repoManager->getRepository(User::class)->findOneBy($filters);
    }

}