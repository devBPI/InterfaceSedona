<?php

namespace App\Model;
use App\Model\Search\Criteria;
use JMS\Serializer\Annotation as JMS;

/**
 * Class Criteria
 * @package App\Model\Search
 * @JMS\XmlRoot("business-search-criterias")
 */
class BusinessSearchCriterias
{
    /**
     * @JMS\Type("App\Model\Search\Criteria")
     */
    private ?Criteria $criteria;

    /**
     * @JMS\Type("App\Model\Facets")
     * @JMS\SerializedName("restrictions-facets")
     */
    public ?Facets $facets;

    public function getCriteria(): Criteria
    {
        return $this->criteria;
    }

    public function __toString(): string
    {
        $advancedSearch = [];
        foreach ($this->getCriteria()->getKeywords() as $criteria) {
            foreach ($criteria as $type => $value) {
                $advancedSearch[] = ['text' => $value, 'field' => $type];
            }
        }
        $filters = [];
        foreach ($this->facets->getBusinessSearchFacets() as $facet) {
            $filters[$facet->getName()] = array_map(function ($value) {
                return $value;
            }, $facet->getBusinessSearchValues());
        }

        return http_build_query(['advanced_search' => $advancedSearch, 'filters' => $filters]);
    }

}