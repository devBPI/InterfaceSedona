<?php
declare(strict_types=1);

namespace App\Service\Provider;

use App\Model\AdvancedSearchCriteria;

/**
 * Class AdvancedSearchProvider
 * @package App\Service\Provider
 */
class AdvancedSearchProvider extends AbstractProvider
{
    protected $modelName = AdvancedSearchCriteria::class;

    /**
     * @return object|AdvancedSearchCriteria
     */
    public function getAdvancedSearchCriteria(): AdvancedSearchCriteria
    {
        return $this->hydrateFromResponse('/advanced-search/list-elements', []);
    }

}
