<?php

namespace App\Model;

use App\Model\Interfaces\ValueBPIInterface;
use JMS\Serializer\Annotation as JMS;

/**
 * Class ValueComplement
 * @package App\Model
 */
class ValueComplement implements ValueBpiInterface
{
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("value")
     */
    private $value;
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("complement")
     */
    private $complement;

    /**
     * @return null|string
     */
    public function getValue(): ?string
    {
        if ($this->value && $this->value !== null){
            return $this->value;
        }

        return $this->value;
    }

    /**
     * @return null|string
     */
    public function getComplement(): ?string
    {
        if ($this->complement && $this->complement !== null){
            return $this->complement;
        }

        return $this->complement;
    }

    public function __toString(): string
    {
        return ''.$this->getValue();
    }
}
