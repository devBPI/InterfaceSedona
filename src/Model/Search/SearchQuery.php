<?php

namespace App\Model\Search;


use App\Model\Interfaces\SearchResultInterface;
use App\Model\Traits\SearchResultTrait;
use JMS\Serializer\Annotation as JMS;

/**
 * Class Search
 * @package App\Model
 */
class SearchQuery implements SearchResultInterface
{

    const SORT_DEFAULT = 'DEFAULT';
    const SORT = [
        'pertinence' => self::SORT_DEFAULT,
        'title-asc' => 'TITRE_A_Z',
        'title-desc' => 'TITRE_Z_A',
        'author-asc' => 'AUTHEUR_A_Z',
        'author-desc' => 'AUTHEUR_Z_A',
        'least-recent' => 'OLDER',
        'most-recent' => 'YOUNGER',
    ];

    const ROWS_DEFAULT = 10;
    const ROWS = [
        self::ROWS_DEFAULT,
        25,
        50
    ];

    use SearchResultTrait;

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
     * @var int
     * @JMS\Type("int")
     */
    private $page;

    /**
     * SearchQuery constructor.
     * @param Criteria $criteria
     * @param FacetFilter|null $facets
     */
    public function __construct(Criteria $criteria, FacetFilter $facets = null)
    {
        $this->criteria = $criteria;
        $this->facets = $facets ?? new FacetFilter();
    }

    /**
     * @return FacetFilter
     */
    public function getFacets(): FacetFilter
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
     * @param FacetFilter $facets
     */
    public function setFacets(FacetFilter $facets)
    {
        $this->facets = $facets;
    }

}
