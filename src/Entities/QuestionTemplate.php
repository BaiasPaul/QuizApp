<?php

namespace QuizApp\Entities;

use ReallyOrm\Entity\AbstractEntity;

/**
 * Class QuestionTemplate
 * @package QuizApp\Entities
 */
class QuestionTemplate extends AbstractEntity
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
     * @var string
     * @ORM type
     */
    private $type;

    /**
     * QuestionTemplate constructor.
     */
    public function __construct()
    {
        $this->id = null;
        $this->text = '';
        $this->type = 'Text';
    }

    /**
     * @param mixed $text
     */
    public function setText($text): void
    {
        $this->text = $text;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
