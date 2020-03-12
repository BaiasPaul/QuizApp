<?php


namespace QuizApp\Services;


use QuizApp\Entities\AnswerInstance;
use QuizApp\Entities\QuestionInstance;
use QuizApp\Entities\QuizInstance;

class QuestionInstanceServices extends AbstractServices
{

    public function saveAnswer($answerText, $questionText)
    {
        $answer = new AnswerInstance();
        $answer->setText($answerText);
        $this->repoManager->register($answer);
        $questionInstance = $this->repoManager->getRepository(QuestionInstance::class)->find($questionText);
        $this->repoManager->register($questionInstance);
        $this->repoManager->getRepository(AnswerInstance::class)->saveAnswer($answer,$questionInstance);
    }

    public function getQuestionsAnswered()
    {
        return $this->repoManager->getRepository(QuestionInstance::class)->getAllQuestionsAnswered();
    }
}
