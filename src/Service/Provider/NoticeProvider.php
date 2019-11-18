<?php
declare(strict_types=1);

namespace App\Service\Provider;

use App\Model\NoticeThemed;

/**
 * Class NoticeProvider
 * @package App\Service\Provider
 */
class NoticeProvider extends AbstractProvider
{
    protected $modelName = NoticeThemed::class;

    /**
     * @param string $query
     * @return NoticeThemed
     */
    public function getNotice(string $query): NoticeThemed
    {

        return $this->hydrateFromResponse(sprintf('/details/notice-themed/%s', $query));
    }

}

