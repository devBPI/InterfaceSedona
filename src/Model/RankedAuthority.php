<?php

namespace App\Model;

use App\Model\Interfaces\AuthorityInterface;
use JMS\Serializer\Annotation as JMS;

/**
 * Class RankedAuthority
 * @package App\Model
 */
class RankedAuthority
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
     * @return AuthorityInterface|null
     */
    public function getAuthor(): ?AuthorityInterface
    {
        if ($this->indiceCdu instanceof IndiceCdu && $this->indiceCdu->getName()) {
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
    public function getPermalink():string
    {
        return $this->getAuthor()->getPermalink();
    }

    /**
     * @return int
     */
    public function getRow()
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
    public function getClassName():?string
    {
        return get_class($this->getAuthor());
    }
}
