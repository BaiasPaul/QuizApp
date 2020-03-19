<?php

namespace QuizApp\Repository;

use QuizApp\Entity\QuizTemplate;
use ReallyOrm\Repository\AbstractRepository;

class QuizTemplateRepository extends AbstractRepository
{
    public function getNumberOfQuestions(int $id)
    {
        $query = "SELECT count(*) as entitiesNumber FROM quiztemplatequestiontemplate WHERE quiztemplate_id=:id";
        $dbStmt = $this->pdo->prepare($query);
        $dbStmt->bindParam(':id', $id);

        $dbStmt->execute();

        return $dbStmt->fetch();
    }

    public function getUserId($id)
    {
        $query = "SELECT user_id FROM quiztemplate WHERE id=:id";
        $dbStmt = $this->pdo->prepare($query);
        $dbStmt->bindParam(':id', $id);

        $dbStmt->execute();

        return $dbStmt->fetch();
    }

    public function getSelectedQuestions($quizid)
    {
        $query = "SELECT * FROM quiztemplatequestiontemplate WHERE quiztemplate_id=:id";
        $dbStmt = $this->pdo->prepare($query);
        $dbStmt->bindParam(':id', $quizid);

        $dbStmt->execute();
        $rows = $dbStmt->fetchAll();
        $result = [];

        foreach ($rows as $row) {
            $result[] = $row['questiontemplate_id'];
        }

        return $result;
    }

    public function deleteQuestions($quizid)
    {
        $query = "DELETE FROM quiztemplatequestiontemplate WHERE quiztemplate_id=:id";
        $dbStmt = $this->pdo->prepare($query);
        $dbStmt->bindParam(':id', $quizid);

        $dbStmt->execute();
    }
}
