<?php
declare(strict_types=1);

namespace App\Service\Provider;

use App\Model\Carousel;
use App\Model\CarouselItem;
use App\Model\Exception\ErrorAccessApiException;
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
    public function getHomeList(): ?Carousel
    {
        return $this->getListByThematic('general');
    }

    /**
     * @param string $theme
     * @return Carousel
     */
    public function getListByThematic(string $theme): ?Carousel
    {
        $carousel = null;
        if ($theme === 'actualites-revues'){
            $theme = 'presse';
        }
        try {
            /** @var $carousel Carousel */
            $carousel = $this->hydrateFromResponse('/carousel/'.$theme);
        } catch (XmlErrorException|ErrorAccessApiException $exception) {
            return null;
        }

        return $carousel;
    }
}
