<?php

namespace App\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class Author
 * @package App\Model
 */
class Author
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
     * @JMS\SerializedName("formeRetenue")
     */
    private $name;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("pays")
     * @JMS\XmlList("pays")
     */
    private $countries;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("langues")
     * @JMS\XmlList("langue")
     */
    private $languages;


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
    public function getName(): string
    {
        return trim($this->name, '[]');
    }

    /**
     * @return array
     */
    public function getCountries(): array
    {
        return $this->countries;
    }

    /**
     * @return array
     */
    public function getLanguages(): array
    {
        return $this->languages;
    }

}
