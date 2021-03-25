<?php

namespace App\Model\Search;

use JMS\Serializer\Annotation as JMS;

/**
 * Class FilterFilter
 * @package App\Model\Search
 */
class FilterFilter
{
    const QUERY_NAME = 'filters';

    /**
     * @var array
     * @JMS\Type("array")
     */
    private $attributes = [];

    /**
     * FilterFilter constructor.
     * @param array $request
     */
    public function __construct(array $request = [])
    {
        if (array_key_exists(self::QUERY_NAME, $request)) {
            foreach ($request[self::QUERY_NAME] as $name => $values) {
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
     * @param array $values
     */
    public function set(string $name, array $values = [])
    {
        $this->attributes[$name] = $values;
    }
}
