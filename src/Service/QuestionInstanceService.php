<?php


namespace QuizApp\Service;


use QuizApp\Entity\AnswerInstance;
use QuizApp\Entity\QuestionInstance;
use QuizApp\Entity\QuizInstance;

class QuestionInstanceService extends AbstractService
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
        $id = $this->session->get('quizInstanceId');
        return $this->repoManager->getRepository(QuestionInstance::class)->getAllQuestionsAnswered($id);
    }
}
