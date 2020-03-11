<?php


namespace QuizApp\Services;


use Framework\Contracts\SessionInterface;
use ReallyOrm\Test\Repository\RepositoryManager;

/**
 * Class AbstractServices
 * @package QuizApp\Services
 */
class AbstractServices
{

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var RepositoryManager
     */
    protected $repoManager;

    /**
     * AbstractServices constructor.
     * @param SessionInterface $session
     * @param RepositoryManager $repoManager
     */
    public function __construct(SessionInterface $session, RepositoryManager $repoManager)
    {
        $this->session = $session;
        $this->repoManager=$repoManager;
    }

    public function getName()
    {
        return $this->session->get('name');
    }

    public function getId()
    {
        return $this->session->get('id');
    }
}
