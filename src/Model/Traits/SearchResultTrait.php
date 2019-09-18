<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 11/09/19
 * Time: 14:16
 */

namespace App\Model\Traits;
use JMS\Serializer\Annotation as JMS;
use App\Model\Search\Criteria;

trait SearchResultTrait
{
    /**
     * @var Criteria|null
     * @JMS\Type("App\Model\Search\Criteria")
     */
    private $criteria;

    /**
     * @return Criteria|null
     */
    public function getCriteria(): Criteria
    {
        return $this->criteria;
    }

}