<?php

namespace App\Model;


use App\Model\Interfaces\SearchResultInterface;
use App\Model\Search\Criteria;
use App\Model\Search\FacetFilter;
use App\Model\Traits\SearchResultTrait;
use JMS\Serializer\Annotation as JMS;

/**
 * Class Search
 * @package App\Model
 */
class Search implements SearchResultInterface
{
    use SearchResultTrait;

    /**
     * @var FacetFilter
     * @JMS\Type("App\Model\Search\FacetFilter")
     */
    private $facets;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $title;

    /**
     * @var int
     * @JMS\Type("int")
     */
    private $rows;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $sort;

    /**
     * @var int
     * @JMS\Type("int")
     */
    private $page;

    /**
     * Search constructor.
     * @param string $title
     * @param Criteria $criteria
     * @param FacetFilter $facets
     */
    public function __construct(string $title, Criteria $criteria, FacetFilter $facets)
    {
        $this->title = $title;
        $this->criteria = $criteria;
        $this->facets = $facets;
    }

    /**
     * @return FacetFilter
     */
    public function getFacets(): FacetFilter
    {
        return $this->facets;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return int
     */
    public function getRows(): int
    {
        return $this->rows;
    }

    /**
     * @param int $rows
     * @return self
     */
    public function setRows($rows): self
    {
        $this->rows = $rows;

        return $this;
    }

    /**
     * @return string
     */
    public function getSort(): string
    {
        return $this->sort;
    }

    /**
     * @param string $sort
     * @return self
     */
    public function setSort($sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     * @return self
     */
    public function setPage($page): self
    {
        $this->page = $page;

        return $this;
    }


}
