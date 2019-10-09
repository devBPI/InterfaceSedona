<?php

namespace App\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class Link
 * @package App\Model
 */
class Link
{
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("object-type")
     */
    private $objectType;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $title;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $right;

    /**
     * @var string
     * @JMS\SerializedName("disponibilite")
     * @JMS\Type("string")
     */
    private $availability;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $media;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("used-profil")
     */
    private $usedrofil;

    /**
     * @return string
     */
    public function getObjectType(): string
    {
        return $this->objectType;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getRight(): string
    {
        return $this->right;
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
    public function getMedia(): ?string
    {
        return $this->media;
    }

    /**
     * @return string
     */
    public function getUsedrofil(): string
    {
        return $this->usedrofil;
    }
}
