<?php
declare(strict_types=1);

namespace App\Service\Provider;

use App\Model\AdvancedSearchCriteria;
use App\Model\Exception\ErrorAccessApiException;
use App\Model\Search\SearchQuery;
use JMS\Serializer\Exception\XmlErrorException;

/**
 * Class AdvancedSearchProvider
 * @package App\Service\Provider
 */
class AdvancedSearchProvider extends AbstractProvider
{
    protected $modelName = AdvancedSearchCriteria::class;

    /**
     * @return AdvancedSearchCriteria
     */
    public function getAdvancedSearchCriteria(SearchQuery $search): AdvancedSearchCriteria
    {
        try {
            //return $this->hydrateFromResponse('/advanced-search/list-elements', [
            return $this->hydrateFromResponse('/advanced-search/lists-elements', [
                'criters' => $this->serializer->serialize($search->getCriteria(), 'xml'),
            ]);
        } catch (ErrorAccessApiException|XmlErrorException $exception) {
            return new AdvancedSearchCriteria();
        }

    }

}
