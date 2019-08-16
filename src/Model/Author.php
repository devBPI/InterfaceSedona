<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 14/08/19
 * Time: 15:20
 */

declare(strict_types=1);

namespace App\Model;

use JMS\Serializer\Annotation as JMS;


class Author
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