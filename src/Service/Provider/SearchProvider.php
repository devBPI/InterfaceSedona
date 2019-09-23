<?php
declare(strict_types=1);

namespace App\Service\Provider;

use App\Model\Exception\NoResultException;
use App\Model\Notice;
use App\Model\Results;
use App\Model\Search\SearchQuery;
use App\Service\APIClient\CatalogClient;
use App\Service\ImageService;
use JMS\Serializer\SerializerInterface;
use Twig_Environment;

/**
 * Class SearchProvider
 * @package App\Service\Provider
 */
class SearchProvider extends AbstractProvider
{
    protected $modelName = Results::class;

    /** @var Twig_Environment  */
    private $templating;

    /**
     * SearchProvider constructor.
     * @param CatalogClient $api
     * @param ImageService $imageService
     * @param SerializerInterface $serializer
     * @param Twig_Environment $templating
     */
    public function __construct(CatalogClient $api, ImageService $imageService, SerializerInterface $serializer, Twig_Environment $templating)
    {
        parent::__construct($api, $imageService, $serializer);

        $this->templating = $templating;
    }

    /**
     * @param SearchQuery $search
     * @return mixed
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function getListBySearch(SearchQuery $search): Results
    {
        /** @var Results $searchResult */
        $searchResult = $this->hydrateFromResponse('/search/all',  [
            'criters' => $this->serializer->serialize($search->getCriteria(), 'xml'),
            'facets' => $this->templating->render('search/facet-filters.xml.twig', ['attributes' => $search->getFacets()->getAttributes(), 'translateNames'=>true]),
            'page' => $search->getPage(),
            'sort' => $search->getSort()??'DEFAULT',
            'rows' => $search->getRows()
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
        return $this->hydrateFromResponse('/autocomplete/notices',
            ['word' => $query,],
            $model);
    }
}

