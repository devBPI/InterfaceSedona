<?php

namespace App\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class Carousel
 * @package App\Model
 */
class Carousel
{
    /**
     * @var array
     * @JMS\Type("array<App\Model\CarouselItem>")
     * @JMS\XmlList(entry="element")
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
     * @param array $elements
     * @return self
     */
    public function setElements(array $elements = []): self
    {
        $this->elements = $elements;

        return $this;
    }

}
