<?php


namespace QuizApp\Services;


use QuizApp\Entities\User;
use ReallyOrm\Entity\AbstractEntity;
use ReallyOrm\Entity\EntityInterface;
use ReallyOrm\Hydrator\HydratorInterface;
use ReallyOrm\Repository\RepositoryInterface;
use ReallyOrm\Test\Repository\RepositoryManager;

class UserServices
{
    private $repoManager;

    public function __construct(RepositoryManager $repoManager)
    {
        $this->repoManager = $repoManager;
    }

}