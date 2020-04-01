<?php

namespace QuizApp\Service;

use QuizApp\Entity\QuestionTemplate;
use QuizApp\Entity\QuizTemplate;
use QuizApp\Entity\User;
use QuizApp\Repository\QuestionTemplateRepository;
use ReallyOrm\Entity\EntityInterface;

/**
 * Class QuizTemplateService
 * @package QuizApp\Service
 */
class QuizTemplateService extends AbstractService
{
    /**
     * @param array $questions
     * @return array
     */
    public function createQuestionList(array $questions): array
    {
        $result = [];
        foreach ($questions as $question) {
            $result[] = $this->repoManager->getRepository(QuestionTemplate::class)->findOneBy(['text' => $question]);
        }

        return $result;
    }

    /**
     * This method creates an user
     *
     * @param $name
     * @param $description
     * @param $questions
     * @param $userId
     */
    public function saveQuiz($name, $description, $questions, $userId): void
    {
        $quiz = new QuizTemplate();
        $quiz->setName($name);
        $quiz->setDescription($description);
        $filters = ['name' => $name, 'description' => $description];
        $user = $this->repoManager->getRepository(User::class)->find($userId);
        $result = $this->repoManager->getRepository(QuizTemplate::class)->findOneBy($filters);
        $questionsFound = $this->createQuestionList($questions);
        if (!$result) {
            $this->repoManager->register($quiz);
            $this->repoManager->register($user);
            $this->repoManager->register($questionsFound[0]);
            $quiz->save();
            $this->repoManager->getRepository(QuizTemplate::class)->setForeignKeyId($user, $quiz);
            $this->repoManager->getRepository(QuizTemplate::class)->setEntitiesToTarget($quiz, $questionsFound);
        }
    }

    /**
     * This method updates an existing user
     *
     * @param $quizId
     * @param $name
     * @param $description
     * @param $questions
     */
    public function editQuiz($quizId, $name, $description, $questions): void
    {
        $quiz = $this->repoManager->getRepository(QuizTemplate::class)->find($quizId);
        $quiz->setName($name);
        $quiz->setDescription($description);
        $questionsFound = $this->createQuestionList($questions);
        $this->repoManager->register($quiz);
        $this->repoManager->register($questionsFound[0]);
        $quiz->save();
        $this->repoManager->getRepository(QuizTemplate::class)->deleteQuestions($quiz->getId());
        $this->repoManager->getRepository(QuizTemplate::class)->setEntitiesToTarget($quiz, $questionsFound);
    }

    /**
     * This method returns an array with the attributes of an user for autocomplete in the edit user form
     *
     * @param $id
     * @return array
     */
    public function getParams($id): array
    {
        $quiz = $this->repoManager->getRepository(QuizTemplate::class)->find($id);

        return ['id' => $quiz->getId(), 'name' => $quiz->getName(), 'description' => $quiz->getDescription()];
    }

    /**
     * This method removes a user with the specified id from the database
     *
     * @param $id
     * @return bool
     */
    public function deleteQuiz($id): bool
    {
        $quiz = $this->repoManager->getRepository(QuizTemplate::class)->find($id);
        $this->repoManager->getRepository(QuizTemplate::class)->deleteQuestions($quiz->getId());

        return $this->repoManager->getRepository(QuizTemplate::class)->delete($quiz);
    }

    /**
     * @param $quizId
     * @return mixed
     */
    public function getSelectedQuestions($quizId)
    {
        return $this->repoManager->getRepository(QuizTemplate::class)->getSelectedQuestions($quizId);
    }

}
