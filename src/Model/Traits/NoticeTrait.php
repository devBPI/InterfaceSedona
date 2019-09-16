<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 10/09/19
 * Time: 16:03
 */
declare(strict_types=1);

namespace App\Model\Traits;

use JMS\Serializer\Annotation as JMS;

trait NoticeTrait
{
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("permalink")
     */
    private $permalink;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("type")
     */
    private $type;

    /**
     * @return string
     */
    public function getPermalink(): string
    {
        return $this->permalink;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}