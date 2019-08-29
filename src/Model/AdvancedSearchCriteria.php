<?php

namespace App\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class AdvancedSearchCriteria
 * @package App\Model
 */
class AdvancedSearchCriteria
{
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("bases-recherche")
     * @JMS\XmlList(entry="base-recherche")
     */
    private $bases;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("langues")
     * @JMS\XmlList(entry="langue")
     */
    private $languages;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("types")
     * @JMS\XmlList(entry="type")
     */
    private $documentType;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("supports")
     * @JMS\XmlList(entry="support")
     */
    private $supports;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("genres-musicaux")
     * @JMS\XmlList(entry="genre-musical")
     */
    private $genreMusic;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("genres-films")
     * @JMS\XmlList(entry="genre-film")
     */
    private $genreFilm;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("genres-litteraires")
     * @JMS\XmlList(entry="genre-litteraire")
     */
    private $genreBook;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("publiques")
     * @JMS\XmlList(entry="publique")
     */
    private $publicTarget;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("secteurs")
     * @JMS\XmlList(entry="secteur")
     */
    private $sector;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("mediations")
     * @JMS\XmlList(entry="mediation")
     */
    private $mediation;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("contenus")
     * @JMS\XmlList(entry="contenu")
     */
    private $content = [];

    /**
     * @return array
     */
    public function getBases(): array
    {
        return $this->bases;
    }

    /**
     * @return array
     */
    public function getLanguages(): array
    {
        return $this->languages;
    }

    /**
     * @return array
     */
    public function getDocumentType(): array
    {
        return $this->documentType;
    }

    /**
     * @return array
     */
    public function getSupports(): array
    {
        return $this->supports;
    }

    /**
     * @return array
     */
    public function getGenreMusic(): array
    {
        return $this->genreMusic;
    }

    /**
     * @return array
     */
    public function getGenreFilm(): array
    {
        return $this->genreFilm;
    }

    /**
     * @return array
     */
    public function getGenreBook(): array
    {
        return $this->genreBook;
    }

    /**
     * @return array
     */
    public function getPublicTarget(): array
    {
        return $this->publicTarget;
    }

    /**
     * @return array
     */
    public function getSector(): array
    {
        return $this->sector;
    }

    /**
     * @return array
     */
    public function getMediation(): array
    {
        return $this->mediation;
    }

    /**
     * @return array
     */
    public function getContent(): array
    {
        return $this->content;
    }
}
