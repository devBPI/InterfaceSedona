<?php
declare(strict_types=1);

namespace App\Model;
use JMS\Serializer\Annotation as JMS;

/**
 * Class SuggestionList
 * @package App\Model
 */
class SuggestionList
{
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("mot-original")
     */
    private $originalWord;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\XmlList(entry="suggestion")
     */
    private $suggestions=[];

    /**
     * @return array
     */
    public function getSuggestions()
    {
        return array_map(function (string $item) {
            return html_entity_decode($item);
        }, $this->suggestions);
    }

    /**
     * @return null|string
     */
    public function getOriginalWord(): ?string
    {
        return $this->originalWord;
    }

}
