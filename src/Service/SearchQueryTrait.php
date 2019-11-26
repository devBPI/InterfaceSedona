<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 04/11/19
 * Time: 10:42
 */

namespace App\Service;

use App\Model\Search\Criteria;
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
        if (($searchQuery = $request->getSession()->get($token))===null){
            return new SearchQuery(new Criteria());
        }

        /** @var SearchQuery $search */
        return $this
            ->serializer
            ->deserialize(
                $searchQuery,
                SearchQuery::class,
                'json'
            );
    }

}

