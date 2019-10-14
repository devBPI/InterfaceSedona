<?php

namespace App\Model;

use App\Model\Interfaces\NoticeInterface;
use JMS\Serializer\Annotation as JMS;

/**
 * Class RankedAuthority
 * @package App\Model
 */
class RankedAuthority implements NoticeInterface
{
    /**
     * @var int
     * @JMS\Type("integer")
     */
    private $rank;

    /**
     * @var IndiceCdu
     * @JMS\Type("App\Model\IndiceCdu")
     */
    private $indiceCdu;

    /**
     * @var Authority
     * @JMS\Type("App\Model\Authority")
     */
    private $authority;

    /**
     * @return NoticeInterface|null
     */
    public function getAuthor(): ?NoticeInterface
    {
        if ($this->indiceCdu instanceof IndiceCdu && $this->indiceCdu->getTitle()) {
            return $this->indiceCdu;
        }

        return $this->authority;
    }

    /**
     * @return int
     */
    public function getRank(): int
    {
        return $this->rank;
    }

    /**
     * @return Authority|null
     */
    public function getAuthority(): ?Authority
    {
        return $this->authority;
    }

    /**
     * @return string
     */
    public function getPermalink(): string
    {
        return $this->getAuthor()->getPermalink();
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->getAuthor()->getTitle();
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->getAuthor()->getType();
    }

    /**
     * @return int
     */
    public function getRow(): int
    {
        return $this->getRank();
    }

    /**
     * @return IndiceCdu
     */
    public function getIndiceCdu(): IndiceCdu
    {
        return $this->indiceCdu;
    }

    /**
     * @return null|string
     */
    public function getClassName(): ?string
    {
        return get_class($this->getAuthor());
    }
}
