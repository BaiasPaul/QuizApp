<?php

namespace QuizApp\Service;

use Framework\Http\Request;
use QuizApp\Entity\AnswerTemplate;
use QuizApp\Entity\QuestionTemplate;
use QuizApp\Entity\User;
use ReallyOrm\Entity\EntityInterface;

/**
 * Class QuestionTemplateService
 * @package QuizApp\Services
 */
class QuestionTemplateService extends AbstractService
{

    /**
     * This method creates a question template and an answer template with the specified parameters
     *
     * @param $text
     * @param $type
     * @param $answerText
     */
    public function saveQuestion($text, $type, $answerText)
    {
        //create a new QuestionTemplate with the specified attributes
        $question = new QuestionTemplate();
        $question->setText($text);
        $question->setType($type);

        //creates a AnswerTemplate with the specified text
        $answer = new AnswerTemplate();
        $answer->setText($answerText);
        $filters = ['text' => $text, 'type' => $type];

        //search for the question in the Database and saves it if not found
        $questionFound = $this->repoManager->getRepository(QuestionTemplate::class)->findOneBy($filters);
        if (!$questionFound) {
            $this->repoManager->register($question);
            $this->repoManager->register($answer);
            $question->save();
            $answer->save();
            $this->repoManager->getRepository(AnswerTemplate::class)->setForeignKeyId($question, $answer);
        }
    }

    /**
     * This method returns a list of questions that have the text LIKE the searched one
     *
     * @param $text
     * @param $currentPage
     * @param $resultsPerPage
     * @return mixed
     */
    public function getQuestionsByText($text, $currentPage, $resultsPerPage)
    {
        return $this->repoManager->getRepository(QuestionTemplate::class)->getQuestionsByText($text, ($currentPage - 1) * $resultsPerPage, $resultsPerPage);
    }

    /**
     * This method searches for a question to update it and adds it a new answer if it does not have one
     * than saves it in the database
     *
     * @param $id
     * @param $text
     * @param $type
     * @param $answerText
     */
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
        //sets the foreign key of the answer with the question id
        $this->repoManager->getRepository(AnswerTemplate::class)->setForeignKeyId($question, $answer);
    }

    /**
     * This method returns an array with the attributes of a question for autocomplete in the edit question form
     *
     * @param $id
     * @return array
     */
    public function getParams($id)
    {
        $question = $this->repoManager->getRepository(QuestionTemplate::class)->find($id);
        $answer = $this->repoManager->getRepository(AnswerTemplate::class)->findOneBy(['questiontemplate_id' => $id]);

        return ['id' => $question->getId(), 'text' => $question->getText(), 'type' => $question->getType(), 'answer' => $answer->getText()];
    }

    /**
     * This method removes a user with the specified id from the database
     *
     * @param $id
     * @return bool
     */
    public function deleteQuestion($id)
    {
        $question = $this->repoManager->getRepository(QuestionTemplate::class)->find($id);

        return $this->repoManager->getRepository(QuestionTemplate::class)->delete($question);
    }

    /**
     * This method return the number of questions with the searched text
     *
     * @param $text
     * @return int
     */
    public function getQuestionNumberOfPagesByText($text)
    {
        return $this->repoManager->getRepository(QuestionTemplate::class)->getQuestionNumberByText($text);
    }

}
