<?php


namespace QuizApp\Repository;

use ReallyOrm\Repository\AbstractRepository;

class UserRepository extends AbstractRepository
{
    public function getQuestions($from, $size)
    {
        $query = "SELECT * FROM quizinstance LIMIT :limit OFFSET :offset;";
        $dbStmt = $this->pdo->prepare($query);
        $dbStmt->bindParam(':limit', $size);
        $dbStmt->bindParam(':offset', $from);

        $dbStmt->execute();

        return $dbStmt->fetchAll();
    }
}
