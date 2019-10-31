<?php

namespace App\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class FacetValue
 * @package App\Model
 */
class FacetValue
{
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $name;

    /**
     * @var integer
     * @JMS\Type("integer")
     */
    private $countOnline;

    /**
     * @var integer
     * @JMS\Type("integer")
     */
    private $countOffline;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->countOnline + $this->countOffline;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }
}
