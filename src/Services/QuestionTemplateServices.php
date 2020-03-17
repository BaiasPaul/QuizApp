<?php

namespace QuizApp\Services;

use QuizApp\Entities\AnswerTemplate;
use QuizApp\Entities\QuestionTemplate;
use QuizApp\Entities\User;

class QuestionTemplateServices extends AbstractServices
{

    public function saveQuestion($text, $type, $answerText)
    {
        $question = new QuestionTemplate();
        $question->setText($text);
        $question->setType($type);
        $answer = new AnswerTemplate();
        $answer->setText($answerText);
        $filters = ['text' => $text, 'type' => $type];
        $questionFound = $this->repoManager->getRepository(QuestionTemplate::class)->findOneBy($filters);
        if (!$questionFound) {
            $this->repoManager->register($question);
            $this->repoManager->register($answer);
            $question->save();
            $answer->save();
            $this->repoManager->getRepository(AnswerTemplate::class)->setForeignKeyId($question, $answer);
        }
    }

    public function getEmptyParams()
    {
        return ['text' => '', 'type' => '', 'answer' => ''];
    }

    public function getQuestionNumberOfPages(array $filters)
    {
        $pages =  $this->repoManager->getRepository(QuestionTemplate::class)->getNumberOfEntities($filters)['entitiesNumber']/5;
        if ($pages % 5 > 0){
            $pages += 1;
        }
        return $pages;
    }

    public function getQuestions(array $filters, $currentPage)
    {
        return $this->repoManager->getRepository(QuestionTemplate::class)->findBy($filters, [], ($currentPage - 1) * 5, 5);
    }

    public function getQuestionsBtText($text, $currentPage)
    {
        return $this->repoManager->getRepository(QuestionTemplate::class)->getQuestionsByText($text, ($currentPage - 1) * 5, 5);
    }

    public function editQuestion($id, $text, $type, $answerText)
    {
        $question = $this->repoManager->getRepository(QuestionTemplate::class)->find($id);
        $question->setText($text);
        $question->setType($type);
        $answer = $this->repoManager->getRepository(AnswerTemplate::class)->findOneBy(['questiontemplate_id' => $id]);
        if (!$answer) {
            $answer = new AnswerTemplate();
        }
        $answer->setText($answerText);
        $this->repoManager->register($question);
        $this->repoManager->register($answer);
        $question->save();
        $answer->save();
        $this->repoManager->getRepository(AnswerTemplate::class)->setForeignKeyId($question, $answer);
    }

    public function getParams($id)
    {
        $question = $this->repoManager->getRepository(QuestionTemplate::class)->find($id);
        $answer = $this->repoManager->getRepository(AnswerTemplate::class)->findOneBy(['questiontemplate_id' => $id]);
        return ['id' => $question->getId(), 'text' => $question->getText(), 'type' => $question->getType(), 'answer' => $answer->getText()];
    }

    public function deleteQuestion($id)
    {
        $question = $this->repoManager->getRepository(QuestionTemplate::class)->find($id);

        return $this->repoManager->getRepository(QuestionTemplate::class)->delete($question);
    }

    public function getQuestionNumberOfPagesByText($text)
    {
        $pages = $this->repoManager->getRepository(QuestionTemplate::class)->getQuestionNumberByText($text)['entitiesNumber']/5;
        if ($pages % 5 > 0){
            $pages += 1;
        }
        return $pages;
    }

}
