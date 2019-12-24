<?php
declare(strict_types=1);

namespace App\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class AroundIndex
 * @package App\Model
 */
final class AroundIndex
{
    /**
     * @var array|Cdu[]
     * @JMS\Type("array<App\Model\Cdu>")
     * @JMS\SerializedName("around-indexes")
     * @JMS\XmlList("index")
     */
    private $indexes;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("next")
     */
    private $next;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("previous")
     */
    private $previous;

    /**
     * @return Cdu[]|array
     */
    public function getIndexes()
    {
        return $this->indexes;
    }

    /**
     * @return string
     */
    public function getNext(): ?string
    {
        return $this->next;
    }

    /**
     * @return string
     */
    public function getPrevious(): ?string
    {
        return $this->previous;
    }

}
