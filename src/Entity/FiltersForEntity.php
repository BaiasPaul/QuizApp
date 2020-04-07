<?php

namespace QuizApp\Entity;

use ReallyOrm\Entity\AbstractEntity;

/**
 * Class FiltersForEntity
 * @package QuizApp\Entity
 */
class FiltersForEntity extends AbstractEntity
{
    /**
     * @var array
     */
    private $filters;

    /**
     * @var int
     */
    private $limit;

    /**
     * @var int
     */
    private $offset;

    /**
     * @var string
     */
    private $orderBy;

    /**
     * @var string
     */
    private $sortType;

    /**
     * FiltersForEntity constructor.
     * @param $filters
     * @param $limit
     * @param $offset
     * @param $orderBy
     * @param $sortType
     */
    public function __construct(
        array $filters = [],
        int $limit = 9999999999,
        int $offset = 0,
        string $orderBy = "id",
        string $sortType = "asc"
    )
    {
        $this->filters = $filters;
        $this->limit = $limit;
        $this->offset = $offset;
        $this->orderBy = $orderBy;
        $this->sortType = $sortType;
        if ($orderBy === ""){
            $this->orderBy = "id";
        }
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @return string
     */
    public function getOrderBy(): string
    {
        return $this->orderBy;
    }

    /**
     * @return string
     */
    public function getSortType(): string
    {
        return $this->sortType;
    }
}
