<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 30/08/19
 * Time: 17:19
 */

namespace App\Model;

use App\Model\Interfaces\ValueBPIInterface;
use JMS\Serializer\Annotation as JMS;

/**
 * Class Value
 * @package App\Model
 */
class Value implements ValueBPIInterface
{
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $value;

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}