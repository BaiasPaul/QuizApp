<?php

namespace QuizApp\Service;

use Framework\Contracts\SessionInterface;
use Framework\Http\Request;
use QuizApp\Entity\FiltersForEntity;
use ReallyOrm\Entity\EntityInterface;
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
     * This method returns a list of entities that have the fieldName LIKE the searched one
     *
     * @param string $class
     * @param EntityInterface $filtersForEntity
     * @return array
     */
    public function getEntitiesByField(string $class, EntityInterface $filtersForEntity): array
    {
        return $this->repoManager->getRepository($class)->getEntitiesByField($filtersForEntity);
    }

    /**
     * @return SessionInterface
     */
    public function getSession(): SessionInterface
    {
        return $this->session;
    }
}
