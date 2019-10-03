<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 26/09/19
 * Time: 15:40
 */

namespace App\Model\Traits;

use JMS\Serializer\Annotation as JMS;

trait OriginTrait
{
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("origines")
     * @JMS\XmlList("origine")
     */
    private $origins;

    /**
     * @return array
     */
    public function getOrigins(): array
    {
        return $this->origins;
    }
}
