<?php

namespace App\Model;


/**
 * Class Carousel
 * @package App\Model
 */
class Carousel
{
    /**
     * @var CarouselItem[]
     */
    private $elements = [];

    /**
     * @return array
     */
    public function getElements(): array
    {
        return $this->elements;
    }

    /**
     * @param CarouselItem $element
     */
    public function addElement(CarouselItem $element)
    {
        $this->elements[] = $element;
    }

    /**
     * @param array $elements
     * @return self
     */
    public function setElements(array $elements=[]): self
    {
        $this->elements = $elements;

        return $this;
    }

}
