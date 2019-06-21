<?php

namespace App\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class Facets
 * @package App\Model
 */
class Facets
{
    /**
     * @var array|Facet[]
     * @JMS\Type("array<App\Model\Facet>")
     * @JMS\SerializedName("facetsList")
     * @JMS\XmlList(entry="facet")
     */
    private $facetsList = [];

    /**
     * @return Facet[]|array
     */
    public function getFacets()
    {
        return $this->facetsList;
    }

}
