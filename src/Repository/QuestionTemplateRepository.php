<?php

namespace QuizApp\Repository;

use ReallyOrm\Repository\AbstractRepository;

class QuestionTemplateRepository extends AbstractRepository
{
    public function getQuestionsByText($text, int $from, int $size)
    {
        $query = 'SELECT * FROM ' . $this->getTableName() . ' WHERE text LIKE :text LIMIT :limit OFFSET :offset;';
        $dbStmt = $this->pdo->prepare($query);
        $dbStmt->bindParam(':limit', $size);
        $dbStmt->bindParam(':offset', $from);
        $dbStmt->bindValue(':text', "%$text%");
        $dbStmt->execute();
        $rows = $dbStmt->fetchAll();
        $result = [];

        foreach ($rows as $row) {
            $result[] = $this->hydrator->hydrate($this->entityName, $row);
        }

        return $result;
    }

    public function getQuestionNumberByText($text)
    {
        $query = "SELECT count(*) as entitiesNumber FROM " . $this->getTableName() . " WHERE text LIKE :text";
        $dbStmt = $this->pdo->prepare($query);
        $dbStmt->bindValue(':text', "%$text%");
        $dbStmt->execute();

        return $dbStmt->fetch();
    }
}
