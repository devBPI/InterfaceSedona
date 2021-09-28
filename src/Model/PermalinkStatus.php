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
     */
    private $status;
    /**
     * @var string
     * @JMS\Type("string")
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
    public function setStatus(string $status): Status
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPermalink()
    {
        return $this->permalink;
    }

    /**
     * @param mixed $permalink
     * @return PermalinkStatus
     */
    public function setPermalink($permalink)
    {
        $this->permalink = $permalink;
        return $this;
    }
}
