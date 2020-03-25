<?php

namespace QuizApp\Service;

use QuizApp\Entity\QuestionInstance;
use QuizApp\Entity\QuizInstance;
use QuizApp\Entity\User;
use QuizApp\Service\AbstractService;

class ResultService extends AbstractService
{
    /**
     * Will be moved in another issue
     *
     * @param int $currentPage
     * @return mixed
     */
    public function getQuestionsInfo(int $currentPage)
    {
        return $this->repoManager->getRepository(User::class)->getQuestions(($currentPage - 1) * 5, 5);
    }

    /**
     * Will be moved in another issue
     *
     * @param $id
     * @return mixed
     */
    public function getQuestionsAnswered($id)
    {
        return $this->repoManager->getRepository(QuestionInstance::class)->getAllQuestionsAnswered($id);
    }

    /**
     * Will be moved in another issue
     *
     * @param $score
     */
    public function setScore($score)
    {
        $quizInstance = $this->repoManager->getRepository(QuizInstance::class)->find($this->session->get('quizInstanceId'));
        $quizInstance->setScore($score);
        $quizInstance->setRepositoryManager($this->repoManager);
        $quizInstance->save();
    }

}