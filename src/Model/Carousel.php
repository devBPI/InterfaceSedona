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
     * @var array|CarouselItem[]
     * @JMS\Type("array<App\Model\CarouselItem>")
     * @JMS\XmlList(entry="element")
     */
    private $elements = [];

    /**
     * @return array|CarouselItem[]
     */
    public function getElements(): array
    {
        return $this->elements;
    }

}
