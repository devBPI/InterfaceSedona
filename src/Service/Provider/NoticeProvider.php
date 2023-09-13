<?php
declare(strict_types=1);

namespace App\Service\Provider;

use App\Model\NoticeThemed;
use App\Model\Status;
use App\Service\NoticeBuildFileService;

/**
 * Class NoticeProvider
 * @package App\Service\Provider
 */
class NoticeProvider extends AbstractProvider
{
    protected $modelName = NoticeThemed::class;

    public function getNotice(string $query, ?string $shortType = null): NoticeThemed
    {
        if ($shortType !== null && $shortType === NoticeBuildFileService::SHORT_PRINT) {
            return $this->hydrateFromResponse(sprintf('/details/notice-short/%s', $query));
        }

        return $this->hydrateFromResponse(sprintf('/details/notice-themed/%s', $query));
    }

    /**
     * @param string $query
     * @return Status
     */
    public function checkNotice(string $query): Status
    {
        return $this->hydrateFromResponse(sprintf('/CatalogueWebService/check/notice/%s', $query),[], Status::class);
    }
}

