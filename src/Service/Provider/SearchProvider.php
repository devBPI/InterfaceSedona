<?php
declare(strict_types=1);

namespace App\Service\Provider;

use App\Model\Notice;
use App\Model\Results;
use App\Model\Search\SearchQuery;
use App\Service\APIClient\CatalogClient;
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
     * @param SerializerInterface $serializer
     * @param Twig_Environment $templating
     */
    public function __construct(CatalogClient $api, SerializerInterface $serializer, Twig_Environment $templating)
    {
        parent::__construct($api, $serializer);

        $this->templating = $templating;
    }

    /**
     * @param SearchQuery $search
     * @return Results
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

        return $searchResult;
    }
    /**
     * @param string $type
     * @param string $word
     * @param string $model
     * @return object
     */
    public function findNoticeAutocomplete(string $type, string $word, string $model)
    {
        /** @var Results $searchResult */
        return $this->hydrateFromResponse('/autocomplete/notices',
            ['word' => $word, 'criteria' => $type],
            $model);
    }
}

