<?php


namespace QuizApp\Service;


use Framework\Contracts\SessionInterface;
use ReallyOrm\Test\Repository\RepositoryManager;

/**
 * Class AbstractService
 * @package QuizApp\Service
 */
class AbstractService
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
     * AbstractService constructor.
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
