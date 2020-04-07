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

    public function sortUsers(string $field, string $ascOrDesc)
    {
        $query = "SELECT * from user GROUP BY :field :ascOfDesc";
        $dbStmt = $this->pdo->prepare($query);
        $dbStmt->bindParam(':field', $field);
        $dbStmt->bindParam(':ascOfDesc', $ascOrDesc);

        $dbStmt->execute();

        return $dbStmt->fetchAll();
    }
}
