<?php
declare(strict_types=1);

namespace App\Service\Provider;

use App\Model\AroundIndex;
use App\Model\Authority;
use App\Model\IndiceCdu;
use App\Model\NoticeMappedAuthority;
use App\Model\Status;
use App\Service\NoticeBuildFileService;

/**
 * Class NoticeProvider
 * @package App\Service\Provider
 */
class NoticeAuthorityProvider extends AbstractProvider
{
    /**
     * @param int $id
     * @return NoticeMappedAuthority
     *
     */
    public function getSubjectNotice(int $id): NoticeMappedAuthority
    {
        return $this->hydrateFromResponse(
            '/details/authority/'.$id.'/notices/notices-sujets',
            [],
            NoticeMappedAuthority::class
        );
    }

    /**
     * @param int $id
     * @return NoticeMappedAuthority
     */
    public function getAuthorsNotice(int $id): NoticeMappedAuthority
    {
        return $this->hydrateFromResponse(
            '/details/authority/'.$id.'/notices/notices-auteurs',
            [],
            NoticeMappedAuthority::class
        );
    }

    /**
     * @param string $query
     * @return Authority
     */
    public function getAuthority(string $query, $shortType=null): Authority
    {
        if($shortType!==null && $shortType===NoticeBuildFileService::SHORT_PRINT){
            return $this->hydrateFromResponse(sprintf('/details/authority-short/%s', $query), [], Authority::class);
        }
        
        return $this->hydrateFromResponse(sprintf('/details/authority/%s', $query), [], Authority::class);
    }

    /**
     * @param string $query
     * @param null $shortType
     * @return IndiceCdu
     */
    public function getIndiceCdu(string $query, $shortType=null): IndiceCdu
    {
        if($shortType!==null && $shortType===NoticeBuildFileService::SHORT_PRINT){
            return $this->hydrateFromResponse(sprintf('/details/indice-cdu-short/%s', $query), [], Authority::class);
        }
        return $this->hydrateFromResponse(sprintf('/details/indice-cdu/%s', $query), [], IndiceCdu::class);
    }

    /**
     * @param int $id
     * @return NoticeMappedAuthority
     */
    public function getSubjectIndice(int $id): NoticeMappedAuthority
    {
        return $this->hydrateFromResponse(
            '/details/indice-cdu/'.$id.'/notices/notices-sujets',
            [],
            NoticeMappedAuthority::class
        );
    }

    /**
     * @param string $cote
     * @return AroundIndex
     */
    public function getIndiceCduAroundOf(string $cote): AroundIndex
    {
        return $this->hydrateFromResponse(
            '/cdu-indexes/around',
            [
                "cduindex"=> $cote
            ],
            AroundIndex::class
        );
    }
    /**
     * @param string $query
     * @return Status
     */
    public function checkNotice(string $query): Status
    {
        return $this->hydrateFromResponse(sprintf('/CatalogueWebService/check/authority/%s', $query),[], Status::class);
    }
    /**
     * @param string $query
     * @return Status
     */
    public function checkIndice(string $query): Status
    {
        return $this->hydrateFromResponse(sprintf('/CatalogueWebService/check/indice-cdu/%s', $query), [], Status::class);
    }
}

