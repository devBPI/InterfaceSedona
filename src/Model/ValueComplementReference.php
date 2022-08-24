<?php

namespace App\Model;

use App\Model\Interfaces\ValueBPIInterface;
use JMS\Serializer\Annotation as JMS;

/**
 * Class ValueComplementReference
 * @package App\Model
 */
class ValueComplementReference implements ValueBPIInterface
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
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("reference")
     */
    private $reference;

    /**
     * @return null|string
     */
    public function getValue(): ?string
    {
        if ($this->value && $this->value !== null) {
            return $this->value;
        }

        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return null|string
     */
    public function getComplement(): ?string
    {
        if ($this->complement && $this->complement !== null) {
            return $this->complement;
        }

       return $this->complement;
    }

    public function setComplement($complement)
    {
        $this->complement = $complement;
    }

    /**
     * @return null|string
     */
    public function getReference(): ?string
    {
        if ($this->reference && $this->reference !== null) {
            return $this->reference;
        }

        return $this->reference;
    }

    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    public function __toString(): string
    {
        return ''.$this->getValue();
    }
}

