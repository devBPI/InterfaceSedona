<?php

namespace App\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class Notice
 * @package App\Model
 */
class Notice
{
    private const SEPARATOR = ' ; ';

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
     * @JMS\SerializedName("titresAnalytiques")
     * @JMS\XmlList("titreAnalytique")
     */
    private $analyticalTitles;

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
     * @JMS\SerializedName("realisateurs")
     * @JMS\XmlList("realisateur")
     */
    private $directors;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("datesTextuelles")
     * @JMS\XmlList("dateTextuelle")
     */
    private $dates;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("resumes")
     * @JMS\XmlList("resume")
     */
    private $resume;

    /**
     * @var array|NoticeAvailable[]
     * @JMS\Type("array<App\Model\NoticeAvailable>")
     * @JMS\SerializedName("exemplaires")
     * @JMS\XmlList("exemplaire")
     */
    private $copies = [];

    /**
     * @var string
     * @JMS\Exclude()
     */
    private $thumbnail;

    /**
     * @var string
     * @JMS\Exclude()
     */
    private $cover;

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
    public function getIsbn(): ?string
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
    public function getFrontTitle(): string
    {
        return implode(self::SEPARATOR, $this->titles);
    }

    /**
     * @return string
     */
    public function getFrontAnalyticalTitle(): string
    {
        return implode(self::SEPARATOR, $this->analyticalTitles);
    }

    /**
     * @return string|null
     */
    public function getFrontAuthor(): string
    {
        return implode(self::SEPARATOR, array_merge($this->authors, $this->directors));
    }

    /**
     * @return string|null
     */
    public function getFrontDate(): string
    {
        if ($this->type === 'Revue') {
            return max($this->dates);
        }

        return implode(self::SEPARATOR, $this->dates);
    }

    /**
     * @return string
     */
    public function getFrontResume(): string
    {
        return implode('. ', $this->resume);
    }

    /**
     * @return NoticeAvailable[]|array
     */
    public function getCopies(): array
    {
        return $this->copies;
    }

    /**
     * @return string
     */
    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    /**
     * @param string $thumbnail
     * @return $this
     */
    public function setThumbnail(string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    /**
     * @return string
     */
    public function getCover(): ?string
    {
        return $this->cover;
    }

    /**
     * @param string $cover
     * @return self
     */
    public function setCover(string $cover): self
    {
        $this->cover = $cover;

        return $this;
    }
}
