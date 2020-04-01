<?php

namespace QuizApp\Util;

/**
 * Class Paginator
 * @package QuizApp\Services
 */
class Paginator
{
    /**
     * @var int
     */
    private $totalResults;
    /**
     * @var int
     */
    private $totalPages;
    /**
     * @var int
     */
    private $currentPage;
    /**
     * @var int
     */
    private $resultsPerPage;

    /**
     * Paginator constructor.
     * @param int $totalResults
     * @param int $currentPage
     * @param int $resultsPerPage
     */
    public function __construct(int $totalResults, int $currentPage = 1, int $resultsPerPage = 5)
    {
        $this->totalResults = $totalResults;
        $this->setCurrentPage($currentPage);
        $this->setResultsPerPage($resultsPerPage);
        $this->totalPages = ceil($totalResults / $resultsPerPage);
    }

    /**
     * Sets the number of results
     *
     * @param int $totalResults
     */
    public function setTotalResults(int $totalResults)
    {
        $this->totalResults = $totalResults;
    }

    /**
     * Sets the number of results that should be displayed on a page
     * and updates the number of available pages accordingly.
     *
     * @param int $resultsPerPage
     */
    public function setResultsPerPage(int $resultsPerPage): void
    {
        $this->resultsPerPage = $resultsPerPage;
        $this->setTotalPages($this->totalResults, $resultsPerPage);
    }

    /**
     * Calculates the number of available pages.
     *
     * @param int $totalResults
     * @param int $resultsPerPage
     */
    public function setTotalPages(int $totalResults, int $resultsPerPage)
    {
        $this->totalPages = ceil($totalResults / $resultsPerPage);
    }

    /**
     * Sets the current page, ensuring that only non-negative and
     * non-zero values are possible.
     *
     * @param $currentPage
     */
    public function setCurrentPage($currentPage): void
    {
        $this->currentPage = $currentPage <= 0 ? 1 : $currentPage;
    }

    /**
     * Returns the next page number or null if there are no more pages
     * available.
     *
     * @return int|null
     */
    public function getNextPage()
    {
        if ($this->currentPage < $this->totalPages) {
            return $this->currentPage + 1;
        }

        return null;
    }

    /**
     * Returns the previous page number or null if there are no more pages
     * available.
     *
     * @return int|null
     */
    public function getPreviousPage()
    {
        if ($this->currentPage > 0) {
            return $this->currentPage - 1;
        }

        return null;
    }

    /**
     * This method should be called only after setTotalPages()
     *
     * @return int
     */
    public function getTotalPages()
    {
        return (int)$this->totalPages;
    }

    /**
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }
}
