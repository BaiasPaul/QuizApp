<?php


namespace QuizApp\Service;


use QuizApp\Entity\AnswerInstance;
use QuizApp\Entity\QuestionInstance;
use QuizApp\Entity\QuizInstance;

class QuestionInstanceService extends AbstractService
{

    public function saveAnswer($answerText, $questionInstanceId)
    {
        $answer = new AnswerInstance();
        $this->repoManager->register($answer);
        $answerFound = $this->repoManager->getRepository(AnswerInstance::class)->findOneBy(['questioninstance_id' => $questionInstanceId]);
        if ($answerFound) {
            $answer = $answerFound;
        }
        $answer->setText($answerText);
        $questionInstance = $this->repoManager->getRepository(QuestionInstance::class)->find($questionInstanceId);
        $this->repoManager->register($questionInstance);
        $this->repoManager->getRepository(AnswerInstance::class)->saveAnswer($answer, $questionInstance);
    }

    public function getQuestionsAnswered()
    {
        //TODO modify after injecting the Session class in Controller
        $id = $this->session->get('quizInstanceId');

        return $this->repoManager->getRepository(QuestionInstance::class)->getAllQuestionsAnswered($id);
    }
}
