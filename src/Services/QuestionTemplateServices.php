<?php

namespace QuizApp\Services;

use QuizApp\Entities\QuestionTemplate;
use QuizApp\Entities\User;

class QuestionTemplateServices extends AbstractServices
{

    public function saveQuestion($text, $type)
    {
        $question = new QuestionTemplate();
        $question->setText($text);
        $question->setType($type);
        $filters = ['text' => $text, 'type' => $type];
        $result = $this->repoManager->getRepository(QuestionTemplate::class)->findOneBy($filters);
        if (!$result) {
            $this->repoManager->register($question);
            $question->save();

            return $result;
        }
        $this->repoManager->register($result);
        $result->save();

        return $result;
    }

    public function getEmptyParams()
    {
        return  ['text' => '', 'type' => ''];
    }

    public function getQuestionNumber(array $filters)
    {
        return $this->repoManager->getRepository(QuestionTemplate::class)->getNumberOfEntities($filters);
    }

    public function getQuestions(array $filters, $currentPage)
    {
        return $this->repoManager->getRepository(QuestionTemplate::class)->findBy($filters, [], ($currentPage - 1) * 5, 5);
    }

    public function editQuestion($id, $text, $type)
    {
        $question = $this->repoManager->getRepository(QuestionTemplate::class)->find($id);
        $question->setText($text);
        $question->setType($type);

        $this->repoManager->register($question);
        $question->save();
    }

    public function getParams($id)
    {
        $question = $this->repoManager->getRepository(QuestionTemplate::class)->find($id);

        return ['id' => $question->getId(), 'text' => $question->getText(), 'type' => $question->getType()];
    }

    public function deleteQuestion($id)
    {
        $question = $this->repoManager->getRepository(QuestionTemplate::class)->find($id);

        return $this->repoManager->getRepository(QuestionTemplate::class)->delete($question);
    }
}
