<?php

namespace App\Model;

use App\Model\Interfaces\ValueBPIInterface;
use JMS\Serializer\Annotation as JMS;

/**
 * Class Value
 * @package App\Model
 */
class Value implements ValueBPIInterface
{
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $value;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $complement;

    /**
     * @return string
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @return null|string
     */
    public function getComplement(): ?string
    {
        return $this->complement;
    }

    public function __toString(): string
    {
        return ''.$this->getValue();
    }
}
