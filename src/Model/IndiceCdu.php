<?php

namespace App\Model;

use App\Model\Interfaces\NoticeInterface;
use App\Model\Interfaces\RecordInterface;
use App\Model\Traits\BreadcrumbTrait;
use App\Model\Traits\IndiceAndAuthorityTrait;
use App\Model\Traits\OriginTrait;
use JMS\Serializer\Annotation as JMS;

/**
 * Class IndiceCdu
 * @package App\Model
 */
final class IndiceCdu  extends Cdu implements NoticeInterface, RecordInterface
{

    const BREAD_CRUMB_NAME = 'indice_cdu';
    private const DOC_TYPE = 'indice';

    use OriginTrait, IndiceAndAuthorityTrait, BreadcrumbTrait;

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
        return self::DOC_TYPE;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->getName();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName();
    }

    /**
     * @return bool
     */
    public function isIndice(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getDocType(): string
    {
        return self::DOC_TYPE;
    }

}

