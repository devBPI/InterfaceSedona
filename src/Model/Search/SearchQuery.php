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
        'default' => self::SORT_DEFAULT,
        'tile_a_z' => 'TITRE_A_Z',
        'tile_z_a' => 'TITRE_Z_A',
        'author_a_z' => 'AUTHEUR_A_Z',
        'author_z_a' => 'AUTHEUR_Z_A',
        'older' => 'OLDER',
        'younger' => 'YOUNGER',
    ];
    const ROWS_DEFAULT = 10;

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
     * @var string
     * @JMS\Exclude
     */
    private $title;

    /**
     * Search constructor.
     * @param string $title
     * @param Criteria $criteria
     * @param FacetFilter $facets
     */
    public function __construct(string $title, Criteria $criteria, FacetFilter $facets = null)
    {
        $this->title = $title;
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
