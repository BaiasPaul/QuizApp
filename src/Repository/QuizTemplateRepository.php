<?php

namespace QuizApp\Repository;

use QuizApp\Entity\QuizTemplate;
use ReallyOrm\Repository\AbstractRepository;

class QuizTemplateRepository extends AbstractRepository
{
    public function getNumberOfQuestions(int $id): int
    {
        $query = "SELECT count(*) as entitiesNumber FROM quiztemplatequestiontemplate WHERE quiztemplate_id=:id";
        $dbStmt = $this->pdo->prepare($query);
        $dbStmt->bindParam(':id', $id);

        $dbStmt->execute();

        return $dbStmt->fetch()['entitiesNumber'];
    }

    public function getUserId($id): int
    {
        $query = "SELECT user_id FROM quiztemplate WHERE id=:id";
        $dbStmt = $this->pdo->prepare($query);
        $dbStmt->bindParam(':id', $id);

        $dbStmt->execute();

        return $dbStmt->fetch()['user_id'];
    }

    public function getSelectedQuestions($quizId): array
    {
        $query = "SELECT * FROM quiztemplatequestiontemplate WHERE quiztemplate_id=:id";
        $dbStmt = $this->pdo->prepare($query);
        $dbStmt->bindParam(':id', $quizId);

        $dbStmt->execute();
        $rows = $dbStmt->fetchAll();
        $result = [];

        foreach ($rows as $row) {
            $result[] = $row['questiontemplate_id'];
        }

        return $result;
    }

    public function deleteQuestions($quizId): void
    {
        $query = "DELETE FROM quiztemplatequestiontemplate WHERE quiztemplate_id=:id";
        $dbStmt = $this->pdo->prepare($query);
        $dbStmt->bindParam(':id', $quizId);

        $dbStmt->execute();
    }
}
