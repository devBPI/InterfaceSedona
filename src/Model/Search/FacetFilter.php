<?php

namespace App\Model\Search;

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
     * @param array $request
     */
    public function __construct(array $request = [])
    {
        if (array_key_exists(self::QUERY_NAME, $request)) {
            foreach ($request[self::QUERY_NAME] as $name => $values) {
                $this->attributes[$name] = $values;
            }
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
