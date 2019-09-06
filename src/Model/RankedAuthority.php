<?php

namespace App\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class RankedAuthority
 * @package App\Model
 */
class RankedAuthority
{
    /**
     * @var string
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
     * @return string
     */
    public function getRank(): string
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
}
