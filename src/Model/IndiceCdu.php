<?php

namespace App\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class IndiceCdu
 * @package App\Model
 */
class IndiceCdu implements AuthorityInterface
{
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("permalink")
     */
    private $id;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $cote;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("libelle")
     */
    private $name;


    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCote(): string
    {
        return $this->cote;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return trim($this->name, '[]');
    }


}
