<?php

namespace App\Model;
use JMS\Serializer\Annotation as JMS;

class Resource
{

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName('parcours')
     */
    private $parcours;

    /**
     * @var string
     * JMS\Type('string')
     * @JMS\SerializedName('theme')
     */
    private $theme;

    /**
     * @return string
     */
    public function getParcours(): string
    {
        return $this->parcours;
    }

    /**
     * @param string $parcours
     */
    public function setParcours(string $parcours): void
    {
        $this->parcours = $parcours;
    }

    /**
     * @return string
     */
    public function getTheme(): string
    {
        return $this->theme;
    }

    /**
     * @param string $theme
     */
    public function setTheme(string $theme): void
    {
        $this->theme = $theme;
    }



}