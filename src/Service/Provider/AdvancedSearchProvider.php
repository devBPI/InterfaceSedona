<?php
declare(strict_types=1);

namespace App\Service\Provider;

use App\Model\AdvancedSearchCriteria;
use App\Model\Exception\ErrorAccessApiException;

/**
 * Class AdvancedSearchProvider
 * @package App\Service\Provider
 */
class AdvancedSearchProvider extends AbstractProvider
{
    protected $modelName = AdvancedSearchCriteria::class;

    /**
     * @return AdvancedSearchCriteria|object
     */
    public function getAdvancedSearchCriteria(): AdvancedSearchCriteria
    {
        try {
            return $this->hydrateFromResponse('/advanced-search/list-elements');
        } catch (ErrorAccessApiException|XmlErrorException $exception) {
            return new AdvancedSearchCriteria();
        }

    }

}
