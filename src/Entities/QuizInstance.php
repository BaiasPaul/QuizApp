<?php


namespace QuizApp\Entities;


/**
 * Class QuizInstance
 * @package QuizApp\Entities
 */
class QuizInstance extends QuizTemplate
{
    private $score;

    /**
     * QuizInstance constructor.
     * @param $score
     */
    public function __construct($score)
    {
        $this->score = $score;
    }

    /**
     * @param mixed $score
     */
    public function setScore($score): void
    {
        $this->score = $score;
    }

}
