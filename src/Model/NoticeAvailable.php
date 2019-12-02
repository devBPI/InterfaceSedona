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
        if ($this->categorie && $this->categorie !== null){
            return $this->categorie;
        }

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
        if ($this->disponibilite && $this->disponibilite !== null){
            return  $this->disponibilite;
        }

        return $this->labelAvailibility;
    }

    /**
     * @return null|string
     */
    public function getLocation(): ?string
    {
        if ($this->localisation && $this->localisation !== null){
            return $this->localisation;
        }

        return $this->location;
    }

    /**
     * @return null|string
     */
    public function getLabelDisponibility(): ?string
    {
       if ($this->labelDisponibility && $this->labelDisponibility !== null){
           return $this->labelDisponibility;
       }

       return $this->labelAvailibility;
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
        return $this->cote??$this->getCallNum();
    }

}
