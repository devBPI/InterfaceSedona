<?php
declare(strict_types=1);

namespace App\Service\Provider;

use App\Model\Carousel;
use App\Model\CarouselItem;
use JMS\Serializer\Exception\XmlErrorException;

/**
 * Class CarouselProvider
 * @package App\Service\Provider
 */
class CarouselProvider extends AbstractProvider
{
    protected $modelName = Carousel::class;

    /**
     * @return Carousel
     */
    public function getHomeList(): Carousel
    {
        return $this->getListByThematic('general');
    }

    /**
     * @param string $theme
     * @return Carousel
     */
    public function getListByThematic(string $theme): Carousel
    {
        try {
            /** @var $carousel Carousel */
            $carousel = $this->hydrateFromResponse('/carousel/'.$theme);

            foreach ($carousel->getElements() as $carouselItem) {
                /** @var $carouselItem CarouselItem */
                if (!empty($carouselItem->getImagePath())) {
                    $carouselItem->setImagePath($this->saveLocalImageFromUrl($carouselItem->getImagePath(),'carousel-'.$theme ));
                }
            }
        } catch (XmlErrorException $exception) {
            return null;
        }

        return $carousel;
    }
}
