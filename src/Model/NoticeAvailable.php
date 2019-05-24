<?php

namespace App\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class NoticeAvailable
 * @package App\Model
 */
class NoticeAvailable
{
    private const LABEL_AVAILABLE = 'Disponible';

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $category;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $callNum;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $availability;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $location;

    /**
     * @return bool
     */
    public function isAvailable(): bool
    {
        return $this->availability === self::LABEL_AVAILABLE;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @return string
     */
    public function getCallNum(): string
    {
        return $this->callNum;
    }

    /**
     * @return string
     */
    public function getAvailability(): string
    {
        return $this->availability;
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

}
