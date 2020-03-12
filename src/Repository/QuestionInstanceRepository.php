<?php


namespace QuizApp\Repository;


use QuizApp\Entities\QuestionInstance;
use ReallyOrm\Repository\AbstractRepository;

class QuestionInstanceRepository extends AbstractRepository
{
    public function insertQuestions(array $questions, $quizInstance)
    {
        $this->deleteOldQuestions();
        foreach ($questions as $question) {
            $this->insertOnDuplicateKeyUpdate($question);
            $this->setForeignKeyId($quizInstance, $question);
        }
    }

    public function getNumberOfQuestions(int $id)
    {
        $query = "SELECT count(*) as questionNumber FROM questioninstance WHERE quizinstance_id=:id";
        $dbStmt = $this->pdo->prepare($query);
        $dbStmt->bindParam(':id', $id);

        $dbStmt->execute();

        return $dbStmt->fetch()['questionNumber'];
    }

    public function getQuestion($id)
    {
        $query = "SELECT text FROM questioninstance LIMIT 1 OFFSET :offset; ";
        $dbStmt = $this->pdo->prepare($query);
        $dbStmt->bindParam(':offset', $id);

        $dbStmt->execute();

        return $dbStmt->fetch()['text'];
    }

    public function deleteOldQuestions()
    {
        $query = "DELETE FROM questioninstance;";
        $dbStmt = $this->pdo->prepare($query);

        $dbStmt->execute();
    }

    public function getAllQuestionsAnswered($id){
        $query = "SELECT  questioninstance.text as question,answerinstance.text as answer FROM quizinstance 
                INNER JOIN  questioninstance ON quizinstance.id = questioninstance.quizinstance_id 
                INNER JOIN answerinstance ON answerinstance.questioninstance_id = questioninstance.id where quizinstance.id=:id;
                ";
        $dbStmt = $this->pdo->prepare($query);
        $dbStmt->bindParam(':id', $id);

        $dbStmt->execute();

        return $dbStmt->fetchAll();
    }

}
