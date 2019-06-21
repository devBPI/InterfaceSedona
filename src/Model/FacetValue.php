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
    public function getCountOnline(): int
    {
        return $this->countOnline;
    }

    /**
     * @return int
     */
    public function getCountOffline(): int
    {
        return $this->countOffline;
    }


}
