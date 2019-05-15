<?php

namespace App\Service\Provider;

use App\Model\Carousel;
use App\Model\CarouselItem;

/**
 * Class CarouselProvider
 * @package App\Service\Provider
 */
class CarouselProvider extends AbstractProvider
{
    protected $modelName = Carousel::class;

    /**
     * @return mixed
     */
    public function getHomeList()
    {
        /** @var $carousel Carousel */
        $carousel = $this->hydrateFromResponse('/carousel/general');

//        foreach ($carousel->getElements() as $carouselItem) {
//            dump($carouselItem);
////            /** @var $carouselItem CarouselItem */
////            $carouselItem->setImagePath($this->saveLocalImage($carouselItem->getImagePath(),'carousel-home' ));
//        }

        return $carousel;
    }
}
