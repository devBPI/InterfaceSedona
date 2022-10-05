<?php
declare(strict_types=1);

namespace App\Service\Provider;

use App\Model\BusinessSearchCriterias;

/**
 * Class NoticeProvider
 * @package App\Service\Provider
 */
class EssentialsResourceProvider extends AbstractProvider
{
    /**
     * @param string $criteria
     * @return BusinessSearchCriterias
     */
    public function getEssentialResource(string $criteria): BusinessSearchCriterias
    {
        return $this->hydrateFromResponse(
            '/essentiels/getcriteria/'.$criteria,
            [],
            BusinessSearchCriterias::class
        );
    }
}