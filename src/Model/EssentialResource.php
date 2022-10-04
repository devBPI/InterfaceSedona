<?php

namespace App\Model;
use JMS\Serializer\Annotation as JMS;
use App\Model\Resource;
class EssentialResource
{

    /**
     * @var Resource
     * @JMS\Type("App\Model\Resource")
     * @JMS\SerializedName('criteria')
     */
    private $criteria;

    /**
     * @return Resource
     */
    public function getCriteria(): Resource
    {
        return $this->criteria;
    }


}