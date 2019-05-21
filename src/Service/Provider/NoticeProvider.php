<?php
declare(strict_types=1);

namespace App\Service\Provider;

use App\Model\ListNotices;
use App\Model\ListOnlineNotices;

/**
 * Class NoticeProvider
 * @package App\Service\Provider
 */
class NoticeProvider extends AbstractProvider
{
    protected $modelName = ListNotices::class;

    /**
     * @param string $query
     * @return mixed
     */
    public function getListBySearch(string $query): ListNotices
    {
        return $this->hydrateFromResponse('/search/notices', [
            'criters' => $this->formatQuery($query)
        ]);
    }

    /**
     * @param string $query
     * @return mixed
     */
    public function getListOnlineBySearch(string $query): ListOnlineNotices
    {
        return $this->hydrateFromResponse(
            '/search/notices-online',
            ['criters' => $this->formatQuery($query)],
            ListOnlineNotices::class
        );
    }

    /**
     * @param string $query
     * @return string
     */
    private function formatQuery(string $query): string
    {
        return <<<EOF
<?xml version="1.0"?>
<search-criterias>
    <parcours>general</parcours>
    <page>1</page>
    <rows>20</rows>
    <general>$query</general>
</search-criterias>
EOF;
    }
}
