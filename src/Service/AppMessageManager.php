<?php


namespace QuizApp\Service;


use Framework\Session\Session;

/**
 * Class AppMessageManager
 * @package QuizApp\Service
 */
class AppMessageManager
{
    /**
     * @var Session
     */
    private $session;

    /**
     * AppMessageManager constructor.
     * @param $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * All error messages received through this method will be stored in session as an array
     *
     * @param string $message
     */
    public function addErrorMessage(string $message): void
    {
        $messages = $this->session->get('errorMessages');
        $messages[] = $message;
        $this->session->set('errorMessages',$messages);
    }

    /**
     * This method gets all error messages from session
     * Removes existing ones
     * Returns error messages
     *
     * @return array
     */
    public function getErrorMessages(): array
    {
        $messages = $this->session->get('errorMessages');
        $this->session->set('errorMessages',[]);

        return $messages;
    }

    /**
     * @return int
     */
    public function getErrorMessagesCount(): int
    {
        if (!$this->session->get('errorMessages')){
            return 0;
        }
        return count($this->session->get('errorMessages'));
    }

    /**
     * All success messages received through this method will be stored in session as an array
     *
     * @param string $message
     */
    public function addSuccessMessage(string $message): void
    {
        $messages = $this->session->get('successMessages');
        $messages[] = $message;
        $this->session->set('successMessages',$messages);
    }

    /**
     * This method gets all success messages from session
     * Removes existing ones
     * Returns error messages
     *
     * @return array
     */
    public function getSuccessMessages(): array
    {
        $messages = $this->session->get('successMessages');
        $this->session->set('successMessages',[]);

        return $messages;
    }

    /**
     * @return int
     */
    public function getSuccessMessagesCount(): int
    {
        return count($this->session->get('successMessages'));
    }

}