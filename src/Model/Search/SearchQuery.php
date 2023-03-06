<?php

namespace App\Model\Search;


use App\Model\Interfaces\SearchResultInterface;
use App\Model\Traits\SearchResultTrait;
use JMS\Serializer\Annotation as JMS;

/**
 * Class SearchQuery
 * @package App\Model\Search
 */
class SearchQuery implements SearchResultInterface
{

    const SORT_DEFAULT = 'DEFAULT';
    const SORT = [
        'pertinence' => self::SORT_DEFAULT,
        'title-asc' => 'TITRE_A_Z',
        'title-desc' => 'TITRE_Z_A',
        'author-asc' => 'AUTEUR_A_Z',
        'author-desc' => 'AUTEUR_Z_A',
        'most-recent' => 'YOUNGER',
        'least-recent' => 'OLDER',
    ];

    const ROWS_DEFAULT = 10;
    const ROWS = [
        self::ROWS_DEFAULT,
        25,
        50
    ];

    const SIMPLE_MODE = 'simple';
    const ADVANCED_MODE = 'advanced';

    use SearchResultTrait;

    /**
     * @var FilterFilter
     * @JMS\Type("App\Model\Search\FilterFilter")
     */
    private $filters;

    /**
     * @var FacetFilter
     * @JMS\Type("App\Model\Search\FacetFilter")
     */
    private $facets;

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
     * @var string
     * @JMS\Type("string")
     */
    private $seeAll;

    /**
     * @var int
     * @JMS\Type("int")
     */
    private $page;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $mode;

    /**
     * SearchQuery constructor.
     * @param Criteria $criteria
     * @param FilterFilter|null $filters
     * @param FacetFilter|null $facets
     * @param string $mode
     */
    public function __construct(Criteria $criteria, FilterFilter $filters = null, FacetFilter $facets = null, string $mode = self::SIMPLE_MODE)
    {
        $this->criteria = $criteria;
        $this->filters = $filters ?? new FilterFilter();
        $this->facets = $facets ?? new FacetFilter();
        $this->mode = $mode;
    }

    /**
     * @return FilterFilter
     */
    public function getFilters(): ?FilterFilter
    {
        return $this->filters;
    }
    /**
     * @return FacetFilter
     */
    public function getFacets(): ?FacetFilter
    {
        return $this->facets;
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
        $this->page = $page > 1 ? $page : 1;

        return $this;
    }

    /**
     * @param FilterFilter $filters
     * @return SearchQuery
     */
    public function setFilters(FilterFilter $filters):SearchQuery
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * @param FacetFilter $facets
     * @return SearchQuery
     */
    public function setFacets(FacetFilter $facets):SearchQuery
    {
        $this->facets = $facets;

        return $this;
    }

    /**
     * @param Criteria $criteria
     */
    public function setCriteria(Criteria $criteria)
    {
        $this->criteria = $criteria;
    }

    /**
     * @return string
     */
    public function getMode(): ?string
    {
        return $this->mode;
    }

    /**
     * @return string
     */
    public function getSeeAll(): string
    {
        return $this->seeAll;
    }

    /**
     * @param string|null $seeAll
     * @return SearchQuery
     */
    public function setSeeAll(string $seeAll=null): SearchQuery
    {
        $this->seeAll = $seeAll;

        return $this;
    }


}
