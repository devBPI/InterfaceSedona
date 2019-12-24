<?php
declare(strict_types=1);

namespace App\Service\Provider;

use App\Model\AroundIndex;
use App\Model\Authority;
use App\Model\IndiceCdu;
use App\Model\NoticeMappedAuthority;

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
    public function getAuthority(string $query): Authority
    {
        return $this->hydrateFromResponse(sprintf('/details/authority/%s', $query), [], Authority::class);
    }

    /**
     * @param string $query
     * @return IndiceCdu
     */
    public function getIndiceCdu(string $query): IndiceCdu
    {
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
}

