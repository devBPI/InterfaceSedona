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
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("supports")
     * @JMS\XmlList("support")
     */
    private $support;


    /**
     * @return bool
     */
    public function isAvailable(): bool
    {
        return $this->availability === self::LABEL_AVAILABLE;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @return string
     */
    public function getCallNum(): string
    {
        return $this->callNum;
    }

    /**
     * @return string
     */
    public function getAvailability(): string
    {
        return $this->availability;
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @return string
     */
    public function getLabelDisponibility(): string
    {
        return $this->labelDisponibility;
    }

}
