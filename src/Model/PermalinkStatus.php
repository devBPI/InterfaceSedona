<?php
declare(strict_types=1);

namespace App\Model;
use JMS\Serializer\Annotation as JMS;

/**
 * Class PermalinkStatus
 * @package App\Model
 */
class PermalinkStatus
{
    /**
      * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("status")
     */
    private $status;
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("permalink")
     */
    private $permalink;

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getPermalink(): string
    {
        return $this->permalink;
    }

    public function setPermalink(string $permalink): self
    {
        $this->permalink = $permalink;
        return $this;
    }
}
