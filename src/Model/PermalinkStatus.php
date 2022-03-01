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

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): string
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPermalink(): string
    {
        return $this->permalink;
    }

    /**
     * @param string $permalink
     * @return PermalinkStatus
     */
    public function setPermalink($permalink): string
    {
        $this->permalink = $permalink;
        return $this;
    }
}
