<?php

namespace App\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class Notice
 * @package App\Model
 */
class NoticeDetail
{
    private const SEPARATOR = ' ; ';

    use NoticeMappedTrait;

    /**
     * @var array|Author
     * @JMS\Type("array<App/Model/Author>")
     * @JMS\SerializedName("auteurs")
     * @JMS\XmlList("auteur")
     */
    private $authors;

    /**
     * @var array|Author
     * @JMS\Type("array<App/Model/Author>")
     * @JMS\SerializedName("auteursSecondaires")
     * @JMS\XmlList("auteursSecondaire")
     */
    private $secondAuthors;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("autresEditions")
     * @JMS\XmlList("autresEdition")
     */
    private $otherEdition;
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("nomPubliqueConfiguration")
     */
    private $publicConfiguration;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("datesPublication")
     * @JMS\XmlList("datePublication")
     */
    private $publishedDate;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("contributeurs")
     * @JMS\XmlList("contributeur")
     */
    private $contributors;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("contributeurVideos")
     * @JMS\XmlList("contributeurVideo")
     */
    private $videoContributors;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("permalink")
     */
    private $id;
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("is-online")
     */
    private $isOnline;

    /**
     * @var array|IndiceCdu
     * @JMS\Type("array<App/Model/IndiceCdu>")
     * @JMS\SerializedName("indices")
     * @JMS\XmlList("indice")
     */
    private $indices;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("genres")
     * @JMS\XmlList("genre")
     */
    private $kinds;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("images-info")
     * @JMS\XmlList("image-info")
     */
    private $imageInfo;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $sourceId;

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
     * @JMS\SerializedName("titresAlternatifs")
     * @JMS\XmlList("titresAlternatif")
     */
    private $alternateTitle;
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("sujets")
     */
    private $titleEnRelation;
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("sujets")
     */
    private $titleTogether;

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
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("descriptionsMaterielle")
     * @JMS\XmlList("descriptionMaterielle")
     */
    private $materielDescription;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("langues")
     * @JMS\XmlList("langue")
     */
    private $languages=[];
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("editeurs")
     * @JMS\XmlList("editeur")
     */
    private $publishers=[];
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("links")
     * @JMS\XmlList("link")
     */
    private $links;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("journal-infos")
     * @JMS\XmlList("journal-info")
     */
    private $informations;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("notes")
     * @JMS\XmlList("note")
     */
    private $notes;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("origins")
     * @JMS\XmlList("origin")
     */
    private $origins;
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("resultatDe")
     */
    private $resultTo;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("reunits")
     * @JMS\XmlList("reunit")
     */
    private $reunits;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("isbns")
     * @JMS\XmlList("isbn")
     */
    private $isbns;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("droits-infos")
     * @JMS\XmlList("droits-info")
     */
    private $rightInformation;
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("seriesCollection")
     */
    private $seriesCollection;

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
     * @return array
     */
    public function getIsbns():array
    {
        return $this->isbns;
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

    /**
     * @return array
     */
    public function getTitles(): array
    {
        return $this->titles;
    }

    /**
     * @return array
     */
    public function getAnalyticalTitles(): array
    {
        return $this->analyticalTitles;
    }

    /**
     * @return array
     */
    public function getAuthors(): array
    {
        return $this->authors;
    }

    /**
     * @return array
     */
    public function getDirectors(): array
    {
        return $this->directors;
    }

    /**
     * @return array
     */
    public function getDates(): array
    {
        return $this->dates;
    }

    /**
     * @return array
     */
    public function getResume(): array
    {
        return $this->resume;
    }
}

