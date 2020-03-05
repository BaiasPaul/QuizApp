<?php


namespace QuizApp\Entities;

class QuestionTemplate
{
    private $id;
    private $text;
    private $type;

    /**
     * QuestionTemplate constructor.
     * @param $id
     * @param $text
     * @param $type
     */
    public function __construct($id, $text, $type)
    {
        $this->id = $id;
        $this->text = $text;
        $this->type = $type;
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
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}
