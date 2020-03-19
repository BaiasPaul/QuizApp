<?php


namespace QuizApp\Entity;


use ReallyOrm\Entity\AbstractEntity;

/**
 * Class AnswerTemplate
 * @package QuizApp\Entities
 */
class AnswerTemplate extends AbstractEntity
{
    /**
     * @var int
     * @ID
     * @ORM id
     */
    private $id;
    /**
     * @var string
     * @ORM text
     */
    private $text;

    /**
     * AnswerTemplate constructor.
     */
    public function __construct()
    {
        $this->id = null;
        $this->text = '';
    }

    /**
     * @param mixed $text
     */
    public function setText($text): void
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

}
