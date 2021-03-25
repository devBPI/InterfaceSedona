<?php

declare(strict_types=1);

namespace App\Model\Traits;

use JMS\Serializer\Annotation as JMS;

/**
 * Trait NoticeTrait
 * @package App\Model\Traits
 */
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
     * @return string|null
     */
    public function getPermalink(): ?string
    {
        return $this->permalink;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        if($this->type)
            return $this->type;
        else
            return "";
    }

}
