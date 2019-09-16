<?php

namespace App\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class CarouselItem
 * @package App\Model
 */
class CarouselItem
{
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $title;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $description;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $imagePath;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $photoCredit;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $link;

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
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return null|string
     */
    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    /**
     * @return string
     */
    public function getPhotoCredit(): ?string
    {
        return $this->photoCredit;
    }

    /**
     * @return string
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * @param string $imagePath
     * @return self
     */
    public function setImagePath($imagePath): self
    {
        $this->imagePath = $imagePath;

        return $this;
    }
}
