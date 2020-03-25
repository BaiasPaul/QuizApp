<?php

namespace QuizApp\Repository;

use ReallyOrm\Repository\AbstractRepository;

class QuizInstanceRepository extends AbstractRepository
{
    public function getUserId($id)
    {
        $query = "SELECT user_id FROM quizinstance WHERE id=:id";
        $dbStmt = $this->pdo->prepare($query);
        $dbStmt->bindParam(':id', $id);

        $dbStmt->execute();

        return $dbStmt->fetch()['user_id'];
    }
}
