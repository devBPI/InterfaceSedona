<?php

namespace App\Model;

use App\Model\Interfaces\NoticeInterface;
use App\Model\Interfaces\RecordInterface;
use App\Model\Traits\BreadcrumbTrait;
use App\Model\Traits\NoticeMappedTrait;
use App\Model\Traits\NoticeTrait;
use App\Model\Traits\OriginTrait;
use App\Service\ImageBuilderService;
use App\Service\TraitSlugify;
use JMS\Serializer\Annotation as JMS;

/**
 * Class NoticeDetail
 * @package App\Model
 */
class Notice extends AbstractImage implements NoticeInterface, RecordInterface
{
    use OriginTrait, TraitSlugify, NoticeMappedTrait, NoticeTrait, BreadcrumbTrait;

    private const SEPARATOR = ' ; ';
    const BREAD_CRUMB_NAME = 'bibliographic';
    const CATALOGUE_BPI = 'Catalogue Bpi';
    const NOTICE_CONTENU_TYPE = [self::MUSIC, 'Site et base'];
    const NOTICE_CONTENT_REVUE_TYPE = ['Revue, journal', 'Article', 'Revue numérique'];
    const MUSIC = 'Musique';
    const VIDEO = 'Vidéo';

    const TYPE_LIVRE = 'Livre';
    const TYPE_LIVRE_NUMERIQUE = 'Livre numérique';
    const TYPE_BD = 'BD';
    const NOTICE_CONTENT_BOOK_TYPE = [self::TYPE_LIVRE, self::TYPE_LIVRE_NUMERIQUE, self::TYPE_BD];

    const ON_LIGNE   = 'en ligne';
    const ON_SHELF   = 'en rayon';
    const ALL        = 'all';
    const SEE_ONLINE = 'online';
    const SEE_ONSHELF = 'rayon';

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
     * @JMS\SerializedName("conservations")
     * @JMS\XmlList("conservation")
     */
    private $conservations;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("conservations")
     * @JMS\XmlList("conservation")
     */
    private $conservationsLiens;
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
     * @JMS\SerializedName("volumes")
     * @JMS\XmlList("volume")
     */
    private $volumes;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("formats")
     * @JMS\XmlList("format")
     */
    private $formats;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("nomPubliqueConfiguration")
     */
    private $nomPubliqueConfiguration;
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("configurationName")
     */
    private $configurationName;
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("urlPubliqueConfiguration")
     */
    private $urlPubliqueConfiguration;
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("configurationPublicUrl")
     */
    private $configurationPublicUrl;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("categorie")
     */
    private $category;

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
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("conservations")
     * @JMS\XmlList("conservation")
     */
    private $conservation;
    /**
     * @var array
     * @JMS\Type("array<App\Model\Value>")
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
     * @JMS\SerializedName("id")
     */
    private $id;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("isbns")
     * @JMS\XmlList("isbn")
     */
    private $isbns;
    /**
     * @var string|null
     * @JMS\Type("string")
     * @JMS\SerializedName("isbn")
     *
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
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("issues")
     * @JMS\XmlList("issue")
     */
    private $issues;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("premieresPages")
     * @JMS\XmlList("premierePage")
     */
    private $firstPage;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("dernieresPages")
     * @JMS\XmlList("dernierePage")
     */
    private $lastPage;

    /**
     * @return array
     */
    public function getIssues(): array
    {
        return $this->issues;
    }

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
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("periodicites")
     * @JMS\XmlList("periodicite")
     */
    private $periodicity;
    /**
     * @var array|null
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("editeurs")
     * @JMS\XmlList("editeur")
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
     * @var array|Value[]
     * @JMS\Type("array<App\Model\Value>")
     * @JMS\SerializedName("contributeurs")
     * @JMS\XmlList("contributeur")
     */
    private $contributeurs;

    /**
     * @var array|Value[]
     * @JMS\Type("array<App\Model\Value>")
     * @JMS\SerializedName("contributeursVide")
     * @JMS\XmlList("contributeurVideo")
     */
    private $videoContributeurs;

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
     * @JMS\SerializedName("titresAnalytic")
     * @JMS\XmlList("titreAnalytic")
     */
    private $analyticalTitlesInDetail;
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
     * @JMS\XmlList("titreAlternatif")
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
     * @JMS\SerializedName("titresUniform")
     * @JMS\XmlList("titreUniform")
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
     * @JMS\SerializedName("genres")
     * @JMS\XmlList("genre")
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
     * @var Right
     * @JMS\Type("App\Model\Right")
     * @JMS\SerializedName("droits-infos")
     */
    private $rights;

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
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("isni")
     */
    private $isni;

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
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("anneeMaximaleSeriel")
     */
    private $maxPublishedDate;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("autresDates")
     * @JMS\XmlList("autreDate")
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
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("notes")
     * @JMS\XmlList("note")
     */
    private $notes;

    /**
     * @var bool
     * @JMS\Type("bool")
     * @JMS\SerializedName("is-online")
     */
    private $onLine;

    /**
     * @var NoticeAvailable[]|array
     * @JMS\Type("array<App\Model\NoticeAvailable>")
     * @JMS\SerializedName("exemplaires")
     * @JMS\XmlList("exemplaire")
     */
    private $copies;
    /**
     * @var Link[]|array
     * @JMS\Type("array<App\Model\Link>")
     * @JMS\SerializedName("liens")
     * @JMS\XmlList("lien")
     */
    private $links;

    /**
     * @return Link[]|array
     */
    public function getLinks()
    {
        return $this->links;
    }
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
     * @return null|string
     */
    public function getIsni(): ?string
    {
        return $this->isni;
    }

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
     * @return string|null
     */
    public function getTopic(): ?string
    {
        $payload = '';
        if (count($this->topics) > 0){
            $payload = $this->topics[0];
        }

        return $payload;
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
        return $this->rights->__toArray();
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
     * @return array
     */
    public function getTitleInformation(): array
    {
        return $this->titleInformation;
    }


    /**
     * @return null|string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getIsbn():?string
    {
        if (count($this->isbns)>0 && !empty($this->isbns[0])){
            return $this->isbns[0];
        }

        return $this->isbn;
    }

    /**
     * @return array
     */
    public function getIsbns(): ?array
    {
        return $this->isbns;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type ?? 'livre';
    }

    /**
     * @return string|null
     */
    public function getFrontTitle(): ?string
    {
        return implode(self::SEPARATOR, $this->titles);
    }

    /**
     * @return string
     */
    public function getFrontAnalyticalTitle(): string
    {
        if (!empty($this->analyticalTitles)) {
            return implode(self::SEPARATOR, $this->analyticalTitles);
        }

        return implode(self::SEPARATOR, $this->analyticalTitlesInDetail);
    }

    /**
     * @return array
     */
    public function getFrontAuthor(): ?array
    {
        $authors = [];

        if (is_array($this->authors)) {
            $authors = array_merge($authors, $this->authors);
        }

        if (is_array($this->authorsValue)) {
            foreach ($this->authorsValue as $value){
                if ($value instanceof Value && $value->getValue()){
                    $authors[] = $value->getValue();
                }
            }
        }

        if (is_array($this->directors)){
            $authors = array_merge($authors, $this->directors);
        }

        return array_unique(array_filter($authors));
    }

    /**
     * @return string|null
     */
    public function getFrontDate(): ?string
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
    public function getCopies()
    {
        return $this->copies;
    }


    /**
     * @return null|string
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
     * @return null|string
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
    public function getAuthors(): array
    {
        if (count($this->authors)>0 && strlen($this->authors[0]) > 1){
            return $this->authors;
        }

        $authors = $this->getAuthorsValue();
        $payload = [];
        foreach ($authors as $value){
            $payload[] = $value->getValue();
        }
        return $payload;
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
     * TOUT SAUF type=Vidéo
     * @return array
     */
    public function getContributeurs(): ?array
    {
        if ($this->getType() === self::VIDEO) {
            return null;
        }
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
     * @see Voir Ligne 30/31 du fichier de Gabarie
     * @return array
     */
    public function getOtherAuthors(): ?array
    {
        if ($this->type === self::VIDEO) {
            return null;
        }
        return $this->otherAuthors;
    }

    /**
     * @return array
     */
    public function getPublishedDates(): array
    {
        $dates = $this->publishedDates;

        if ($this->maxPublishedDate) {
            $dates[] = $this->maxPublishedDate;
        }

        return $dates;
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
     * @see Voir Ligne 32/33 du fichier gabarit, onglé notice
     * @return array
     */
    public function getConservation(): array
    {

        if ($this->conservation){
            return $this->conservation;
        }

        $payload = [];
        foreach ($this->getLinks() as $value){
            if (($conservation = $value->getConservation()) !== null){
                $payload[] = $conservation;
            }
        }

        return $payload;
    }

    /**
     * @return array|null
     */
    public function getSubject(): ?array
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
     * @return null|string
     */
    public function getIndice(): ?string
    {
        return count($this->indices)>0?($this->indices[0])->getCote():'';
    }


    /**
     * @return null|string
     */
    public function getConfigurationName(): ?string
    {
        if ($this->nomPubliqueConfiguration) {
            return $this->nomPubliqueConfiguration;
        }

        return $this->configurationName;
    }
    /**
     * @return null|string
     */
    public function getConfigurationUrl(): ?string
    {
        if ($this->urlPubliqueConfiguration) {
            return $this->urlPubliqueConfiguration;
        }

        return $this->configurationPublicUrl;
    }

    /**
     * @return array|null
     */
    public function getEditors(): ?array
    {

        return $this->editors;
    }

    /**
     * @return Value[]|array
     */
    public function getVideoContributeurs()
    {
        return $this->videoContributeurs;
    }


    /**
     * @return array|null
     */
    public function getPeriodicity(): ?array
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

    /**
     * @return string
     */
    public function getPermalink(): string
    {
        return $this->permalink;
    }

    public function getClassName()
    {
        return self::class;
    }

    /**
     * @return bool
     */
    public function isOnLine():bool
    {
        if ($this->onLine){
            return $this->onLine;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getTitle():string
    {
         $titles = $this->getTitles();
         if (count($titles) > 0) {
             return $titles[0];
         }

         return 'Notice sans titre';
    }

    /**
     * @return string
     */
    public function getExportTitle(): string
    {
        $payload = [];
        if ($this->getTitleInformation() ){
            $payload[] = implode(',', $this->getTitleInformation());
        }
        if ($this->getIssues() ) {
            $payload[] = implode(',', $this->getIssues());
        }
        if ($this->getVolumes() ) {
            $payload[] = implode(',', $this->getVolumes());
        }

        $result = implode(', ', $payload);
        if ($this->getFirstPage() ) {
            $result .= 'pp. '.implode(',', $this->getFirstPage());
        }
        if ($this->getLastPage() ) {
            $result .= ' - '.implode(',', $this->getLastPage());
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getFirstPage(): array
    {
        return $this->firstPage;
    }

    /**
     * @return array
     */
    public function getLastPage(): array
    {
        return $this->lastPage;
    }

    /**
     * @return array
     */
    public function getVolumes(): array
    {
        return $this->volumes;
    }

    /**
     * @return null|string
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * @return string
     */
    public function getImage(): ?string
    {
        if ($this->getPicture() instanceof Picture && !empty($this->getPicture()->getContent())){
            return $this->getPicture()->getContent();

        }elseif (!empty($this->getIsbn())){
            return $this->getIsbnCover() ;
        }

        return null;
    }

    /**
     * @return string
     */
    public function getIsbnCover()
    {
        return ImageBuilderService::COVER.DIRECTORY_SEPARATOR.$this->getIsbn();
    }

    /**
     * @return string
     */
    public function getIsbnThumbnail()
    {
        return ImageBuilderService::THUMBNAIL.DIRECTORY_SEPARATOR.$this->getIsbn();
    }

    /**
     * @return string
     */
    public function getDefaultImage(): string
    {
        return ImageBuilderService::buildGenericPicture($this->getType());
    }



    public function getSlugifiedType(){
        return  $this->slugify($this->getType());
    }

    /**
     * @return null|string
     */
    public function getFormats(): ?string
    {
        return implode(self::SEPARATOR,$this->formats);
    }

    /**
     * @return array|null
     */
    public function getAnalyticalTitles(): ?array
    {
        return $this->analyticalTitles;
    }

    public function getPrintTitle(): string
    {
        return $this->type.' - '.$this->getTitle();
    }
}

