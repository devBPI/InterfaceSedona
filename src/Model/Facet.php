<?php
declare(strict_types=1);

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
     * @var string
     * @JMS\Type("string")
     */
    private $label;

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
     * @var array|FacetValue[]
     * @JMS\Type("array<App\Model\FacetValue>")
     * @JMS\SerializedName("valuesCounts")
     * @JMS\XmlList("value")
     */
    private $values;

    /**
     * @var array
     * @JMS\Exclude()
     */
    private $raw_values;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
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
     * @return array|FacetValue[]
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @return array
     */
    public function getRawValues(): array
    {
        if ($this->raw_values === null) {
            $this->raw_values = array_map(function (FacetValue $value) {
                return (int)$value->getName();
            }, $this->values);
        }

        return $this->raw_values;
    }

    /**
     * @return int
     */
    public function getMinValue(): int
    {
        return min($this->getRawValues());
    }
    /**
     * @return int
     */
    public function getMaxValue(): int
    {
        return max($this->getRawValues());
    }
}
