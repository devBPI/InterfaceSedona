<?php
declare(strict_types=1);

namespace App\Model;
use JMS\Serializer\Annotation as JMS;
use JMS\Serializer\Annotation\XmlValue;
class Status
{
    /**
     * @JMS\Type("string")
     * @XmlValue
     */
    private $status;

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
}
