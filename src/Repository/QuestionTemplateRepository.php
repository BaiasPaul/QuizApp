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
     * This method looks in the Database for questions with the text LIKE $text
     * and from an offset specified returning the questions found
     *
     * the quantity of questions to be retuned is specified in the $size variable
     *
     * @param $text
     * @param int $from
     * @param int $size
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
     * This method looks in the Database for questions with the text LIKE $text and returns the number of questions found
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
