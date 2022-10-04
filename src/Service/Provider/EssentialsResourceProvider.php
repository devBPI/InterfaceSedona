<?php
declare(strict_types=1);

namespace App\Service\Provider;

use App\Model\AroundIndex;
use App\Model\Authority;
use App\Model\EssentialResource;
use App\Model\IndiceCdu;
use App\Model\NoticeMappedAuthority;
use App\Model\Status;
use App\Service\NoticeBuildFileService;

/**
 * Class NoticeProvider
 * @package App\Service\Provider
 */
class EssentialsResourceProvider extends AbstractProvider
{
    /**
     * @param int $id
     * @return EssentialResource
     *
     */
    public function getEssentialResource(string $criteria): EssentialResource
    {
        return $this->hydrateFromResponse(
            '/essentiels/getcriteria/'.$criteria,
            [],
            EssentialResource::class
        );
    }
}