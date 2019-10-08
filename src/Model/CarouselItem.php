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
    private $creator;

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
    private $type;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $permalink;

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
    public function getCreator(): ?string
    {
        return $this->creator;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    /**
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getPermalink(): ?string
    {
        return $this->permalink;
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
