<?php

namespace App\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class Facet
 * @package App\Model
 */
class Facet
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
     * @var array
     * @JMS\Type("array<App\Model\Facet>")
     * @JMS\SerializedName("valuesCounts")
     * @JMS\XmlList("value")
     */
    private $values;

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

    /**
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }

}
