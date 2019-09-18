<?php
declare(strict_types=1);

namespace App\Service\Provider;

use App\Model\Author;
use App\Model\Authority;
use App\Model\Exception\NoResultException;
use App\Model\ListNotices;
use App\Model\ListOnlineNotices;
use App\Model\Notice;
use App\Model\NoticeMappedAuthority;
use App\Model\Notices;
use App\Model\NoticeThemed;

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
        /** @var ListNotices $notices */
        $notices = $this->hydrateFromResponse('/search/notices', [
            'criters' => $this->formatQuery($query)
        ]);

        foreach ($notices->getNoticesList() as $notice) {
            $this->getImagesForNotice($notice);
        }

        return $notices;
    }

    /**
     * @param string $query
     * @return mixed
     */
    public function getListOnlineBySearch(string $query): ListOnlineNotices
    {
        /** @var ListOnlineNotices $notices */
        $notices = $this->hydrateFromResponse(
            '/search/notices-online',
            ['criters' => $this->formatQuery($query)],
            ListOnlineNotices::class
        );

        foreach ($notices->getNoticesList() as $notice) {
            $this->getImagesForNotice($notice);
        }

        return $notices;
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

    /**
     * @param Notice $notice
     */
    private function getImagesForNotice(Notice $notice): void
    {
        if (!empty($notice->getIsbn())) {

            $notice
                ->setThumbnail($this->getImageAndSaveLocal('vignette', 'notice-thumbnail', $notice->getIsbn()))
                ->setCover($this->getImageAndSaveLocal('couverture', 'notice-cover', $notice->getIsbn()))
            ;
        }
    }

    /**
     * @param string $category
     * @param string $folderName
     * @param string $isbn
     * @return string
     */
    private function getImageAndSaveLocal(string $category, string $folderName, string $isbn): string
    {
        try {
            $content = $this->arrayFromResponse('/electre/'.$category.'/'.$isbn)->getBody()->getContents();

            return $this->saveLocalImageFromContent($content, $folderName, $isbn.'.jpeg');
        } catch (NoResultException $exception) {
            return '';
        }
    }

    public function getNotice(string $query)
    {
        $notices = [];
        try{
            $notices = $this->hydrateFromResponse(sprintf('/details/notice-themed/%s', $query), [], NoticeThemed::class);
          //  $notices = $this->hydrateFromResponse(sprintf('/details/authority/%s', $query), [], NoticeThemed::class);

        }catch(NoResultException $e){
            dump("la ressource n'est plus disponible page 404 customisé à faire");
        }

        return $notices;
    }

}

