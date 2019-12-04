<?php
declare(strict_types=1);

namespace App\Service\Provider;

use App\Controller\SearchController;
use App\Model\Carousel;
use App\Model\CarouselItem;
use App\Model\Exception\ErrorAccessApiException;
use App\WordsList;
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
        return $this->getListByThematic(SearchController::GENERAL);
    }

    /**
     * @param string $theme
     * @return Carousel
     */
    public function getListByThematic(string $theme): ?Carousel
    {
        $carousel = null;
        if ($theme === WordsList::THEME_PRESS){
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
