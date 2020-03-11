<?php

namespace QuizApp\Services;

use QuizApp\Entities\QuizInstance;
use QuizApp\Entities\User;

class QuizInstanceServices extends AbstractServices
{

    public function getQuizzes(array $filters, int $currentPage)
    {
        return $this->repoManager->getRepository(QuizInstance::class)->findBy($filters, [], ($currentPage - 1) * 5, 5);
    }

    public function getQuizzNumber(array $filters)
    {
        return $this->repoManager->getRepository(QuizInstance::class)->getNumberOfEntities($filters);
    }
}
