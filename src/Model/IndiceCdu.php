<?php

namespace App\Model;

use App\Model\Interfaces\AuthorityInterface;
use App\Model\Interfaces\NoticeInterface;
use JMS\Serializer\Annotation as JMS;
use App\Model\Traits\OriginTrait;
/**
 * Class IndiceCdu
 * @package App\Model
 */
class IndiceCdu  extends Cdu implements AuthorityInterface, NoticeInterface
{
    use OriginTrait;
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("permalink")
     */
    private $permalink;

    /**
     * @var int
     * @JMS\Type("int")
     * @JMS\SerializedName("id")
     */
    private $id;

    /**
     *
     * @JMS\Type("App\Model\AroundIndex")
     * @JMS\SerializedName("around-indexes-list")
     */
    private $aroundIndex;

    /**
     * @return AroundIndex
     */
    public function getAroundIndex(): AroundIndex
    {
        return $this->aroundIndex;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

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
        return self::class;
    }

    /**
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->getName();
    }

    public function __toString()
    {
        return $this->getName();
    }
}

