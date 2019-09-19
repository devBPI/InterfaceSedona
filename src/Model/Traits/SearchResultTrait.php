<?php
declare(strict_types=1);

namespace App\Model\Traits;

use JMS\Serializer\Annotation as JMS;
use App\Model\Search\Criteria;

trait SearchResultTrait
{
    /**
     * @var Criteria|null
     * @JMS\SerializedName("criters")
     * @JMS\Type("App\Model\Search\Criteria")
     */
    private $criteria;

    /**
     * @return Criteria|null
     */
    public function getCriteria(): ?Criteria
    {
        return $this->criteria;
    }

}
