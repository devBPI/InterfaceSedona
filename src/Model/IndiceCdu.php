<?php

namespace App\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class IndiceCdu
 * @package App\Model
 */
class IndiceCdu  extends Cdu implements AuthorityInterface
{
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("permalink")
     */
    private $id;


    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}

