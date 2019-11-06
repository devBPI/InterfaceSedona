<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 04/11/19
 * Time: 10:42
 */

namespace App\Service;

use App\Model\Search\FiltersQuery;
use App\Model\Search\ObjSearch;
use App\Model\Search\SearchQuery;
use Symfony\Component\HttpFoundation\Request;

trait SearchQueryTrait
{
    /**
     * @param string $token
     * @param Request $request
     * @return SearchQuery
     */
    public function getSearchQueryFromToken(string $token, Request $request): SearchQuery
    {
        /** @var SearchQuery $search */
        return $this
            ->serializer
            ->deserialize(
                $request->getSession()->get($token),
                SearchQuery::class,
                'json'
            );
    }

}

