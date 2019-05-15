<?php

namespace App\Model;


/**
 * Class CarouselItem
 * @package App\Model
 */
class CarouselItem
{
    /**
     * @var array
     */
    private $element;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $imagePath;

    /**
     * @var string
     */
    private $photoCredit;

    /**
     * @var string
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
     * @param string $title
     * @return self
     */
    public function setTitle($title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return self
     */
    public function setDescription($description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getImagePath(): string
    {
        return $this->imagePath;
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

    /**
     * @return string
     */
    public function getPhotoCredit(): string
    {
        return $this->photoCredit;
    }

    /**
     * @param string $photoCredit
     * @return self
     */
    public function setPhotoCredit($photoCredit): self
    {
        $this->photoCredit = $photoCredit;

        return $this;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @param string $link
     * @return self
     */
    public function setLink($link): self
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @return array
     */
    public function getElement(): array
    {
        return $this->element;
    }

    /**
     * @param array $element
     * @return self
     */
    public function setElement($element): self
    {
        $this->element = $element;

        return $this;
    }

}
