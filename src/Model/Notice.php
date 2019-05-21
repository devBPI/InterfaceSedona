<?php

namespace App\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class Notice
 * @package App\Model
 */
class Notice
{
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("permalink")
     */
    private $id;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $isbn;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $type;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("titres")
     * @JMS\XmlList("titre")
     */
    private $titles;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("auteurs")
     * @JMS\XmlList("auteur")
     */
    private $authors;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("resumes")
     * @JMS\XmlList("resume")
     */
    private $resume;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getIsbn(): string
    {
        return $this->isbn;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        if (count($this->titles) === 0) {
            return null;
        }

        return $this->titles[0];
    }

    /**
     * @return string|null
     */
    public function getAuthor(): ?string
    {
        if (count($this->authors) === 0) {
            return null;
        }

        return $this->authors[0];
    }

    /**
     * @return string
     */
    public function getResume(): string
    {
        return implode('<br>', $this->resume);
    }

}
