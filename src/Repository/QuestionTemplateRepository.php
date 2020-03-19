<?php

namespace QuizApp\Repository;

use ReallyOrm\Repository\AbstractRepository;

/**
 * Class QuestionTemplateRepository
 * @package QuizApp\Repository
 */
class QuestionTemplateRepository extends AbstractRepository
{
    /**
     * Returns questions that contain the value of the $text argument in their text fields.
     * The match is performed using the LIKE comparison operator.
     * The result set is paginated.
     *
     * @param string $text The query string
     * @param int $from The offset
     * @param int $size The size of the result set (LIMIT)
     * @return array
     */
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

    /**
     * Returns number of questions that contain the value of the $text argument in their text fields.
     * The match is performed using the LIKE comparison operator.
     *
     * @param $text
     * @return int
     */
    public function getQuestionNumberByText($text)
    {
        $query = "SELECT count(*) as entitiesNumber FROM " . $this->getTableName() . " WHERE text LIKE :text";
        $dbStmt = $this->pdo->prepare($query);
        $dbStmt->bindValue(':text', "%$text%");
        $dbStmt->execute();

        return $dbStmt->fetch()['entitiesNumber'];
    }
}
