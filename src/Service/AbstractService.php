<?php

namespace QuizApp\Service;

use Framework\Contracts\SessionInterface;
use Framework\Http\Request;
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
        $this->repoManager = $repoManager;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->session->get('name');
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->session->get('id');
    }

    /**
     * Returns the parameter if found, otherwise returns a default
     *
     * @param string $key
     * @param Request $request
     * @param null $default
     * @return array|int
     */
    public function getFromParameter(string $key, Request $request, $default = null)
    {
        return array_key_exists($key, $request->getParameters()) ? $request->getParameter($key) : $default;
    }

    /**
     * This method return the number of entities with the searched text
     *
     * @param $class
     * @param $fields
     * @return int
     */
    public function getEntityNumberOfPagesByField($class, $fields): int
    {
        return $this->repoManager->getRepository($class)->getNumberOfEntitiesByField($fields);
    }


    /**
     * @return SessionInterface
     */
    public function getSession(): SessionInterface
    {
        return $this->session;
    }
}
