<?php

namespace App\Model\Search;

use Symfony\Component\HttpFoundation\Request;
use JMS\Serializer\Annotation as JMS;

/**
 * Class FacetFilter
 * @package App\Model\Search
 */
class FacetFilter
{
    const QUERY_NAME = 'facets';

    /**
     * @var array
     * @JMS\Type("array")
     */
    private $attributes = [];

    /**
     * FacetFilter constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        foreach ($request->get(self::QUERY_NAME, []) as $name => $values) {
            $this->attributes[$name] = $values;
        }
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

}
