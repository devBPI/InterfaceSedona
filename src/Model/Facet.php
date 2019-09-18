<?php
declare(strict_types=1);

namespace App\Model;

use App\Model\Search\FromArrayInterface;
use JMS\Serializer\Annotation as JMS;

/**
 * Class Facet
 * @package App\Model
 */
class Facet implements FromArrayInterface
{
       const MANDATORY_KEYS = [
        'name',
        'label',
        'countOnline',
        'countOffline' ,
        'values',
        'general' ,
        'page' ,
        'rows',
        'title' ,
        'realisateur',
        'subject' ,
        'theme',
        'publicationDate' ,
        'publicationDateStart',
        'publicationDateEnd' ,
        'sort',
    ];
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $name;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $label;

    /**
     * @var integer
     * @JMS\Type("integer")
     */
    private $countOnline;

    /**
     * @var integer
     * @JMS\Type("integer")
     */
    private $countOffline;

    /**
     * @var array|FacetValue[]
     * @JMS\Type("array<App\Model\FacetValue>")
     * @JMS\SerializedName("valuesCounts")
     * @JMS\XmlList("value")
     */
    private $values;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return int
     */
    public function getCountOnline(): int
    {
        return $this->countOnline;
    }

    /**
     * @return int
     */
    public function getCountOffline(): int
    {
        return $this->countOffline;
    }

    /**
     * @return array|FacetValue[]
     */
    public function getValues(): array
    {
        return $this->values;
    }


    /**
     * @param array $array
     * @return mixed
     */
    public static function fromArray($array)
    {

    }
}
