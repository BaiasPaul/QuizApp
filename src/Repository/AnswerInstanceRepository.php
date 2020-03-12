<?php


namespace QuizApp\Repository;


use ReallyOrm\Repository\AbstractRepository;

class AnswerInstanceRepository extends AbstractRepository
{
    public function saveAnswer($answer,$quizInstance)
    {
        $this->deleteOldAnswers();

        $this->insertOnDuplicateKeyUpdate($answer);
        $this->setForeignKeyId($quizInstance, $answer);
    }

    public function deleteOldAnswers()
    {
        $query = "DELETE FROM answerinstance;";
        $dbStmt = $this->pdo->prepare($query);

        $dbStmt->execute();
    }
}
