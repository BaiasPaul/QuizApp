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
     * @return mixed
     */
    public function getName()
    {
        return $this->session->get('name');
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->session->get('id');
    }

    /**
     * Returns the parameter if found, otherwise returns a default
     *
     * @param string $key
     * @param Request $request
     * @param null $default
     * @return mixed|null
     */
    public function getFromParameter(string $key, Request $request, $default = null)
    {
        return array_key_exists($key, $request->getParameters()) ? $request->getParameter($key) : $default;
    }

    /**
     * @param $class
     * @param $fieldName
     * @param $fieldValue
     * @return mixed
     */
    public function getEntityNumberOfPagesByField($class, $fieldName, $fieldValue)
    {
        return $this->repoManager->getRepository($class)->getEntityNumberByField($fieldName, $fieldValue);
    }

    /**
     * @param $class
     * @param $fieldName
     * @param $fieldValue
     * @param $currentPage
     * @param $resultsPerPage
     * @return mixed
     */
    public function getEntitiesByField($class, $fieldName, $fieldValue, $currentPage, $resultsPerPage)
    {
        return $this->repoManager->getRepository($class)->getEntitiesByField($fieldName, $fieldValue, ($currentPage - 1) * $resultsPerPage, $resultsPerPage);
    }
}
