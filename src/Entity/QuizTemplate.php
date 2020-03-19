<?php

namespace QuizApp\Entity;

use ReallyOrm\Entity\AbstractEntity;

/**
 * Class QuizTemplate
 * @package QuizApp\Entities
 */
class QuizTemplate extends AbstractEntity
{
    /**
     * @var int
     * @ID
     * @ORM id
     */
    private $id;

    /**
     * @var string
     * @ORM name
     */
    private $name;

    /**
     * @var string
     * @ORM description
     */
    private $description;

    /**
     * QuizTemplate constructor.
     */
    public function __construct()
    {
        $this->id = null;
        $this->name = '';
        $this->description = '';
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function getNumberOfQuestions()
    {
       return $this->getRepository()->getNumberOfQuestions($this->getId())['entitiesNumber'];
    }

    public function getUserId()
    {
        return $this->getRepository()->getUserId($this->getId())['user_id'];
    }

}
