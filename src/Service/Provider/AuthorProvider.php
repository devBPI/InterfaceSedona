<?php
declare(strict_types=1);

namespace App\Service\Provider;

use App\Model\ListAuthors;

/**
 * Class AuthorProvider
 * @package App\Service\Provider
 */
class AuthorProvider extends AbstractProvider
{
    protected $modelName = ListAuthors::class;

    /**
     * @param string $query
     * @return mixed
     */
    public function getListBySearch(string $query): ListAuthors
    {
        return $this->hydrateFromResponse('/search/relevant-authorities', [
            'criters' => $this->formatQuery($query)
        ]);
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
