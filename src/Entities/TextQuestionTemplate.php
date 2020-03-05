<?php


namespace QuizApp\Entities;


class TextQuestionTemplate
{
    private $id;
    private $text;

    /**
     * TextQuestionTemplate constructor.
     * @param $id
     * @param $text
     */
    public function __construct($id, $text)
    {
        $this->id = $id;
        $this->text = $text;
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
}
