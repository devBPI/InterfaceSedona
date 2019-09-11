<?php

namespace App\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class NoticeAvailable
 * @package App\Model
 */
class NoticeAvailable
{
    private const LABEL_AVAILABLE = 'Disponible';

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("categorie")
     */
    private $category;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $cote;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $callNum;

    /**
     * @var string
     * @JMS\SerializedName("disponibilite")
     * @JMS\Type("string")
     */
    private $availability;

    /**
     * @var string
     * @JMS\SerializedName("localisation")
     * @JMS\Type("string")
     */
    private $location;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("disponibilite-label")
     */
    private $labelDisponibility;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("dernier-numero")
     */
    private $lastNumber;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("supports")
     * @JMS\XmlList("support")
     */
    private $support;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("numeros-recus")
     * @JMS\XmlList("numero")
     */
    private $reciviedNumber;


    /**
     * @return bool
     */
    public function isAvailable(): bool
    {
        return $this->availability === self::LABEL_AVAILABLE;
    }

    /**
     * @return null|string
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * @return null|string
     */
    public function getCallNum(): ?string
    {
        return $this->callNum;
    }

    /**
     * @return null|string
     */
    public function getAvailability(): ?string
    {
        return $this->availability;
    }

    /**
     * @return null|string
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * @return null|string
     */
    public function getLabelDisponibility(): ?string
    {
        return $this->labelDisponibility;
    }

    /**
     * @return array
     */
    public function getSupport(): array
    {
        return $this->support;
    }

    /**
     * @return array
     */
    public function getReciviedNumber(): array
    {
        return $this->reciviedNumber;
    }

    /**
     * @return null|string
     */
    public function getLastNumber(): ?string
    {
        return $this->lastNumber;
    }

    /**
     * @return array
     */
    public function getValuesReciviedNumber(): array
    {
        return array_flip($this->reciviedNumber);
    }

    /**
     * @return string
     */
    public function getCote(): ?string
    {
        return $this->cote;
    }

}
