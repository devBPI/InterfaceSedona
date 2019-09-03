<?php

namespace App\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class NoticeDetail
 * @package App\Model
 */
class Notice
{

    private const SEPARATOR = ' ; ';

    use NoticeMappedTrait;
    /**
     * @var array|Value[]
     * @JMS\Type("array<App\Model\Value>")
     * @JMS\SerializedName("seriesCollection")
     * @JMS\XmlList("serieCollection")
     */
    private $collectionSeries;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("origines")
     * @JMS\XmlList("origine")
     */
    private $origins;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("conservations")
     * @JMS\XmlList("conservation")
     */
    private $conservations;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("epoques")
     * @JMS\XmlList("epoque")
     */
    private $eras;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("nomPubliqueConfiguration")
     */
    private $configurationName;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("titresEnsemble")
     * @JMS\XmlList("titreEnsemble")
     */
    private $togetherTitle;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("titresForm")
     * @JMS\XmlList("titreForm")
     */
    private $titleForm;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("titresEnRelation")
     * @JMS\XmlList("titreEnRelation")
     */
    private $inRelationTitle;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("auteurs")
     * @JMS\XmlList("auteur")
     */
    private $authors;
    /**
     * @var array|Value[]
     * @JMS\Type("array<App\Model\Value>")
     * @JMS\SerializedName("auteurs")
     * @JMS\XmlList("auteur")
     */
    private $authorsValue;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("reunits")
     * @JMS\XmlList("reunit")
     */
    private $meeting;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("autresEditions")
     * @JMS\XmlList("autreEdition")
     */
    private $otherEdition;
    /**
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("conservations")
     * @JMS\XmlList("conservation")
     */
    private $conservation;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("auteursSecondaires")
     * @JMS\XmlList("auteurSecondaire")
     */
    private $otherAuthors;

    /**
     * @var array|Picture[]
     * @JMS\Type("array<App\Model\Picture>")
     * @JMS\SerializedName("images-info")
     * @JMS\XmlList("image-info")
     */
    private $pictures;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("permalink")
     */
    private $id;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("isbns")
     * @JMS\XmlList("isbn")
     */
    private $isbn;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("issns")
     * @JMS\XmlList("issn")

     */
    private $issn;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $type;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $row;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("periodicite")
     */
    private $periodicity;
    /**
     * @var string
     * @JMS\SerializedName("editeurs")
     * @JMS\XmlList("editeur")
     * @JMS\Type("string")
     */
    private $editors;

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
     * @JMS\SerializedName("themes")
     * @JMS\XmlList("theme")
     */
    private $topics;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("contributeurs")
     * @JMS\XmlList("contributeur")
     */
    private $contributeurs;

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
     * @JMS\SerializedName("traductionsDe")
     * @JMS\XmlList("traductionDe")
     */
    private $translatedBy;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("titresAlternatifs")
     * @JMS\XmlList("titreAlternatifs")
     */
    private $alternatifTitle;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("titresJournal")
     * @JMS\XmlList("titreJournal")
     */
    private $titleInformation;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("titresUniforms")
     * @JMS\XmlList("titresUniforms")
     */
    private $uniformTitle;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("sujets")
     * @JMS\XmlList("sujet")
     */
    private $subject;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("langues")
     * @JMS\XmlList("langue")
     */
    private $languages;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("languesOriginales")
     * @JMS\XmlList("langueOriginale")
     */
    private $originalLanguages;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("kinds")
     * @JMS\XmlList("kind")
     */
    private $kinds;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("publiques")
     * @JMS\XmlList("publique")
     */
    private $publics;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("droits")
     * @JMS\XmlList("droit")
     */
    private $rights;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("licences")
     * @JMS\XmlList("licence")
     */
    private $licences;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("copyrights")
     * @JMS\XmlList("copyright")
     */
    private $copyright;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("lieuxManifestations")
     * @JMS\XmlList("lieuManifestation")
     */
    private $placesEvents;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("lieux")
     * @JMS\XmlList("lieu")
     */
    private $locations;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("numerosCommerciaux")
     * @JMS\XmlList("numeroCommercial")
     */
    private $commercialNumbers;
    /**
     * @var Quatrieme|null
     * @JMS\Type("App\Model\Quatrieme")
     * @JMS\SerializedName("quatrieme")
     */
    private $fourth;
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("table-des-matieres")

     */
    private $contentsTable;

    /**
     * @return array
     */
    public function getCommercialNumbers(): array
    {
        return $this->commercialNumbers;
    }

    /**
     * @return array
     */
    public function getTopics(): array
    {
        return $this->topics;
    }



    /**
     * @return array
     */
    public function getPublics(): array
    {
        return $this->publics;
    }

    /**
     * @return array
     */
    public function getRights(): array
    {
        return $this->rights;
    }

    /**
     * @return array
     */
    public function getLicences(): array
    {
        return $this->licences;
    }

    /**
     * @return array
     */
    public function getCopyright(): array
    {
        return $this->copyright;
    }

    /**
     * @return array
     */
    public function getPlacesEvents(): array
    {
        return $this->placesEvents;
    }

    /**
     * @return Quatrieme|null
     */
    public function getFourth(): ?Quatrieme
    {
        return $this->fourth;
    }



    /**
     * @return string|null
     */
    public function getContentsTable(): ?string
    {
        return $this->contentsTable;
    }
    /**
     * @var array|IndiceCdu[]
     * @JMS\Type("array<App\Model\IndiceCdu>")
     * @JMS\SerializedName("indices")
     * @JMS\XmlList("indice")
     */
    private $indices;
    /**
     * @var array#
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("contenus")
     * @JMS\XmlList("contenu")
     */
    private $contents;

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
     * @JMS\SerializedName("datesPublication")
     * @JMS\XmlList("datePublication")
     */
    private $publishedDates;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("datesPublication")
     * @JMS\XmlList("datePublication")
     */
    private $otherDates;

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
    private $materialDescriptions;

    /**
     * @return array
     */
    public function getTitleInformation(): array
    {
        return $this->titleInformation;
    }
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("notes")
     * @JMS\XmlList("note")
     */
    private $notes;

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
     * @return null|string
     */
    public function getIsbn()
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

    /**
     * @return array
     */
    public function getContributeurs(): array
    {
        return $this->contributeurs;
    }

    /**
     * @return Picture[]|array
     */
    public function getPictures()
    {
            return $this->pictures;

    }    /**
     * @return Picture|mixed|null
     */
    public function getPicture()
    {
        if (is_array($this->pictures) && !empty($this->pictures[0]) ){
            return $this->pictures[0];
        }

        return null;
    }

    /**
     * @return array
     */
    public function getOtherAuthors(): array
    {
        return $this->otherAuthors;
    }

    /**
     * @return array
     */
    public function getPublishedDates(): array
    {
        return $this->publishedDates;
    }

    /**
     * @return array
     */
    public function getOtherDates(): array
    {
        return $this->otherDates;
    }

    /**
     * @return array
     */
    public function getAlternatifTitle(): array
    {
        return $this->alternatifTitle;
    }

    /**
     * @return array
     */
    public function getUniformTitle(): array
    {
        return $this->uniformTitle;
    }

    /**
     * @return array
     */
    public function getNotes(): array
    {
        return $this->notes;
    }

    /**
     * @return array
     */
    public function getMaterialDescriptions(): array
    {
        return $this->materialDescriptions;
    }

    /**
     * @return array
     */
    public function getTogetherTitle(): array
    {
        return $this->togetherTitle;
    }

    /**
     * @return array
     */
    public function getMeeting(): array
    {
        return $this->meeting;
    }

    /**
     * @return array
     */
    public function getConservation(): array
    {
        return $this->conservation;
    }

    /**
     * @return array
     */
    public function getSubject(): array
    {
        return $this->subject;
    }

    /**
     * @return array
     */
    public function getIndices(): array
    {
        return $this->indices;
    }

    /**
     * @return array
     */
    public function getConfigurationName(): array
    {
        return $this->configurationName;
    }

    /**
     * @return array
     */
    public function getOrigins(): array
    {
        return $this->origins;
    }

    /**
     * @return string
     */
    public function getEditors(): string
    {
        return $this->editors;
    }

    /**
     * @return null|string
     */
    public function getPeriodicity(): ?string
    {
        return $this->periodicity;
    }

    /**
     * @return array|null
     */
    public function getContents(): ?array
    {
        return $this->contents;
    }

    /**
     * @return array
     */
    public function getCollectionSeries(): array
    {
        return $this->collectionSeries;
    }

    /**
     * @return array
     */
    public function getLanguages(): array
    {
        return $this->languages;
    }

    /**
     * @return Value[]|array
     */
    public function getAuthorsValue()
    {
        return $this->authorsValue;
    }

    /**
     * @return string
     */
    public function getRow(): string
    {
        return $this->row;
    }

    /**
     * @return array
     */
    public function getOriginalLanguages(): array
    {
        return $this->originalLanguages;
    }

    /**
     * @return array
     */
    public function getInRelationTitle(): array
    {
        return $this->inRelationTitle;
    }

    /**
     * @return array
     */
    public function getOtherEdition(): array
    {
        return $this->otherEdition;
    }


    /**
     * @return array
     */
    public function getConservations(): array
    {
        return $this->conservations;
    }

    /**
     * @return array
     */
    public function getEras(): array
    {
        return $this->eras;
    }

    /**
     * @return array
     */
    public function getKinds(): array
    {
        return $this->kinds;
    }

    /**
     * @return array
     */
    public function getIssn()
    {
        return $this->issn;
    }

    /**
     * @return array
     */
    public function getLocations(): array
    {
        return $this->locations;
    }

    /**
     * @return array
     */
    public function getTitleForm(): array
    {
        return $this->titleForm;
    }

    /**
     * @return array
     */
    public function getTranslatedBy(): array
    {
        return $this->translatedBy;
    }
}


