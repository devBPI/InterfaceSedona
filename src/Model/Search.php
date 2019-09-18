<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 11/09/19
 * Time: 14:15
 */

namespace App\Model;


use App\Model\Interfaces\SearchResultInterface;
use App\Model\Search\Criteria;
use App\Model\Search\FacetFilter;
use App\Model\Traits\SearchResultTrait;
use JMS\Serializer\Annotation as JMS;


class Search implements SearchResultInterface
{
    use SearchResultTrait;
    /**
     * @var FacetFilter
     * @JMS\Type("App\Model\Search\FacetFilter")
     */
    private $facets;

    /**
     * @return FacetFilter
     */
    public function getFacets(): FacetFilter
    {
        return $this->facets;
    }

    /**
     * Search constructor.
     * @param Criteria $criteria
     * @param Facets $facets
     */
    public function __construct(Criteria $criteria, FacetFilter $facets)
    {
        $this->criteria = $criteria;
        $this->facets = $facets;
    }

}
