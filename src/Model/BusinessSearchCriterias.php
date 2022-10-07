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

    public function __toString(): string
    {
        $operators = $this->criteria->getOperators();
        $queries = ['advanced_search' => $this->criteria->convertKeywordsToQueries()];
        if (count($operators) > 0) {
            $queries = array_merge($queries, ['advanced_search_operator' => array_reduce(array_keys($operators),function($carry,$key) use($operators){
                $carry[$key+1] = $operators[$key];
                return $carry;
            },[])]);
        }

        $filters = [];
        foreach ($this->facets->getBusinessSearchFacets() as $facet) {
            $filters[$facet->getName()] = array_map(function ($value) {
                return $value;
            }, $facet->getBusinessSearchValues());
        }

        return http_build_query(array_merge($queries, ['filters' => $filters]));
    }
}