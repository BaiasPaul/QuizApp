<?php

namespace QuizApp\Service;

use QuizApp\Entity\AnswerInstance;
use QuizApp\Entity\QuestionInstance;
use QuizApp\Entity\QuestionTemplate;
use QuizApp\Entity\QuizInstance;
use QuizApp\Entity\QuizTemplate;
use QuizApp\Entity\User;

class QuizInstanceService extends AbstractService
{
    public function createQuestionList(array $questions)
    {
        $result = [];
        foreach ($questions as $question) {
            $result[] = $this->getQuestionInstance($this->repoManager->getRepository(QuestionTemplate::class)->find($question));
        }

        return $result;
    }

    public function getQuestionInstance($questionTemplate){
        $questionInstance = new QuestionInstance();
        $questionInstance->setText($questionTemplate->getText());
        $questionInstance->setType($questionTemplate->getType());

        return $questionInstance;
    }

    public function createQuizInstance()
    {
        $quizId = $this->session->get('quizId');
        $userId = $this->getId();
        $quizTemplate = $this->repoManager->getRepository(QuizTemplate::class)->find($quizId);
        $quizInstance = new QuizInstance();
        $quizInstance->setName($quizTemplate->getName());

        $user = $this->repoManager->getRepository(User::class)->find($userId);
        $questions = $this->repoManager->getRepository(QuizTemplate::class)->getSelectedQuestions($quizId);
        $questionsFound = $this->createQuestionList($questions);

        $this->repoManager->register($quizInstance);
        $this->repoManager->register($user);
        foreach ($questionsFound as $question){
            $this->repoManager->register($question);
        }

        $quizInstance->save();
        $this->session->set('quizInstanceId',$quizInstance->getId());
        $this->repoManager->getRepository(QuizTemplate::class)->setForeignKeyId($user, $quizInstance);
        $this->repoManager->getRepository(QuestionInstance::class)->insertQuestions($questionsFound,$quizInstance);
    }

    public function setQuizId($id)
    {
        $this->session->set('quizId',$id);
    }

    public function getQuestion($questionNumber)
    {
        return $this->repoManager->getRepository(QuestionInstance::class)->getQuestion($questionNumber,  $this->session->get('quizInstanceId'));
    }

    public function getQuestionInstanceNumber($question)
    {
        $question = $this->repoManager->getRepository(QuestionInstance::class)->findOneBy(['text'=>$question]);

        return $question->getId();
    }

    public function deleteOldAnswers()
    {
        $this->repoManager->getRepository(AnswerInstance::class)->deleteOldAnswers();
    }

    public function getMaxQuestions()
    {
        return $this->repoManager->getRepository(QuestionInstance::class)->getNumberOfQuestions($this->session->get('quizInstanceId'));
    }

}
