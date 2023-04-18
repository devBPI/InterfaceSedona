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
    const AVAILABLE = 'Disponible';
    const UNAVAILABLE = 'Indisponible';

    /**
     * @var string
     * @JMS\SerializedName("bookbinding")

     * @JMS\Type("string")
     */
    private $bookbinding;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $category;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $categorie;

    /**
     * @var string
     * @JMS\SerializedName("cote")

     * @JMS\Type("string")
     */
    private $cote;

    /**
     * @var string
     * @JMS\SerializedName("call_num")
     * @JMS\Type("string")
     */
    private $callNum;

    /**
     * @var string
     * @JMS\SerializedName("availability")
     * @JMS\Type("string")
     */
    private $availability;


    /**
     * @var string
     * @JMS\SerializedName("disponibilite")
     * @JMS\Type("string")
     */
    private $disponibilite;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $localisation;
    /**
     * @var string
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
     * @JMS\SerializedName("availability-label")
     */
    private $labelAvailibility;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("dernier-numero")
     */
    private $lastNumber;
    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("last_received")
     */
    private $lastReceived;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("supports")
     * @JMS\XmlList("support")
     */
    private $support;
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("material_support")
     */
    private $materialSupport;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("numeros-recus")
     * @JMS\XmlList("numero")
     */
    private $reciviedNumber;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\XmlList("note")
     */
    private $notes;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $note;

    /**
     * @var string $valosite
     * @JMS\Type("string")
     */
    private $valosite;

    /**
     * @return string
     */
    public function getValosite(): ?string
    {
        return $this->valosite;
    }

    /**
     * @return bool
     */
    public function isAvailable(): bool
    {
        return $this->availability === self::LABEL_AVAILABLE || $this->disponibilite === self::AVAILABLE;
    }

    /**
     * @return null|string
     */
    public function getBookbinding(): ?string
    {
        return $this->bookbinding;
    }

    /**
     * @return null|string
     */
    public function getCategory(): ?string
    {
        return $this->categorie ?? $this->category;
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
        return $this->disponibilite ?? $this->availability;
    }

    /**
     * @return null|string
     */
    public function getLocation(): ?string
    {
        return $this->localisation ?? $this->location;
    }

    /**
     * @return null|string
     */
    public function getLabelDisponibility(): ?string
    {
       return $this->labelDisponibility ?? $this->labelAvailibility;
    }

    /**
     * @return string
     */
    public function getSupport(): ?string
    {
        if (!empty($this->materialSupport)) {
            return $this->materialSupport;
        }

        return implode(' ', $this->support);
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
        return $this->lastNumber ?? $this->lastReceived;
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
        return $this->cote??$this->getCallNum();
    }

    /**
     * @return array
     */
    public function getNotes(): array
    {
        if ($this->note) {
            return [$this->note];
        }

        return $this->notes;
    }

    public function getPrintLocation(): ?string
    {
        $location = $this->getLocation();
        if (!empty($this->getCategory())) {
            $location .= ' - '.$this->getCategory();
        }

        return $location;
    }
}
