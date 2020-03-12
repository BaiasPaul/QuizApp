<?php

namespace QuizApp\Repository;

use ReallyOrm\Repository\AbstractRepository;

class AnswerTemplateRepository extends AbstractRepository
{
    public function getAnswerText($id)
    {
        $query = "SELECT text FROM answertemplate WHERE questiontemplate_id=:id";
        $dbStmt = $this->pdo->prepare($query);
        $dbStmt->bindParam(':id', $id);
        $dbStmt->execute();

        return $dbStmt->fetch();
    }

    public function getAnswerForQuestion($id)
    {
        $query = "SELECT * FROM answertemplate WHERE questiontemplate_id=:id";
        $dbStmt = $this->pdo->prepare($query);
        $dbStmt->bindParam(':id', $id);
        $dbStmt->execute();

        return $dbStmt->fetch();
    }
}
