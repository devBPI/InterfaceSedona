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
                if ($name === 'date_publishing') {
                    $values = implode(',', $values);
                }

                $this->set($name, $values);
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

    /**
     * @param string $name
     * @param string $values
     */
    public function set(string $name, string $values)
    {
        $this->attributes[$name] = $values;
    }
}
