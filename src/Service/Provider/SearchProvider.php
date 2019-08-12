<?php
declare(strict_types=1);

namespace App\Service\Provider;

use App\Model\Exception\NoResultException;
use App\Model\Notice;
use App\Model\Results;

/**
 * Class SearchProvider
 * @package App\Service\Provider
 */
class SearchProvider extends AbstractProvider
{
    protected $modelName = Results::class;

    /**
     * @param string $query
     * @return mixed
     */
    public function getListBySearch(string $query): Results
    {
        /** @var Results $searchResult */
        $searchResult = $this->hydrateFromResponse('/search/all', [
            'criters' => $this->formatQuery($query)
        ]);

        foreach ($searchResult->getNotices()->getNoticesList() as $notice) {
            $this->getImagesForNotice($notice);
        }
        foreach ($searchResult->getNoticesOnline()->getNoticesList() as $notice) {
            $this->getImagesForNotice($notice);
        }

        return $searchResult;
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

    /**
     * @param string $query
     * @param string $model
     * @return object
     */
    public function findNoticeAutocomplete(string $query, string $model)
    {
        /** @var Results $searchResult */
        $content = $this->hydrateFromResponse('/autocomplete/notices',
            ['word' => $query,],
            $model);


/*
        foreach ($searchResult->getNotices()->getNoticesList() as $notice) {
            $this->getImagesForNotice($notice);
        }

        foreach ($searchResult->getNoticesOnline()->getNoticesList() as $notice) {
            $this->getImagesForNotice($notice);
        }
*/
        return $content;
    }

}
