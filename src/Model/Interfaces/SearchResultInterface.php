<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 11/09/19
 * Time: 14:14
 */

namespace App\Model\Interfaces;


use App\Model\Search\Criteria;

interface SearchResultInterface
{
    /**
     * @return Criteria|null
     */
    public function getCriteria(): ?Criteria;
}
