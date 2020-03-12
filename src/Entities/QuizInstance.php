<?php


namespace QuizApp\Entities;


use ReallyOrm\Entity\AbstractEntity;

/**
 * Class QuizInstance
 * @package QuizApp\Entities
 */
class QuizInstance extends AbstractEntity
{
    /**
     * @var int
     * @ID
     * @ORM id
     */
    private $id;
    /**
     * @var int
     * @ORM score
     */
    private $score;
    /**
     * @var string
     * @ORM name
     */
    private $name;
    /**
     * @var int
     * @ORM is_saved
     */
    private $isSaved;

    /**
     * QuizInstance constructor.
     */
    public function __construct()
    {
        $this->id = null;
        $this->score = 0;
        $this->name = '';
        $this->isSaved = 0;
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
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @param mixed $score
     */
    public function setScore($score): void
    {
        $this->score = $score;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getIsSaved()
    {
        return $this->isSaved;
    }

    /**
     * @param mixed $isSaved
     */
    public function setIsSaved($isSaved): void
    {
        $this->isSaved = $isSaved;
    }

}
