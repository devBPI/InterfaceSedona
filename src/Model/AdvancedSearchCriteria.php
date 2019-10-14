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
    private $configuration_name = [];

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("langues")
     * @JMS\XmlList(entry="langue")
     */
    private $language = [];

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("types")
     * @JMS\XmlList(entry="type")
     */
    private $type = [];

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("supports")
     * @JMS\XmlList(entry="support")
     */
    private $material_support = [];

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("genres-musicaux")
     * @JMS\XmlList(entry="genre-musical")
     */
    private $genre_musical = [];

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("genres-films")
     * @JMS\XmlList(entry="genre-film")
     */
    private $genre_film = [];

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("genres-litteraires")
     * @JMS\XmlList(entry="genre-litteraire")
     */
    private $genre_litteraire = [];

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("publiques")
     * @JMS\XmlList(entry="publique")
     */
    private $audience = [];

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("secteurs")
     * @JMS\XmlList(entry="secteur")
     */
    private $secteur = [];

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("mediations")
     * @JMS\XmlList(entry="mediation")
     */
    private $mediation = [];

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("contenus")
     * @JMS\XmlList(entry="contenu")
     */
    private $contenu = [];

    /**
     * @return array
     */
    public function getConfigurationName(): array
    {
        return $this->configuration_name;
    }

    /**
     * @return array
     */
    public function getLanguage(): array
    {
        return $this->language;
    }

    /**
     * @return array
     */
    public function getType(): array
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getMaterialSupport(): array
    {
        return $this->material_support;
    }

    /**
     * @return array
     */
    public function getGenreMusical(): array
    {
        return $this->genre_musical;
    }

    /**
     * @return array
     */
    public function getGenreFilm(): array
    {
        return $this->genre_film;
    }

    /**
     * @return array
     */
    public function getGenreLitteraire(): array
    {
        return $this->genre_litteraire;
    }

    /**
     * @return array
     */
    public function getAudience(): array
    {
        return $this->audience;
    }

    /**
     * @return array
     */
    public function getSecteur(): array
    {
        return $this->secteur;
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
    public function getContenu(): array
    {
        return $this->contenu;
    }

    /**
     * @return array
     */
    public function getFacets(): array
    {
        return [
            'type',
            'materialSupport',
            'genreMusical',
            'genreFilm',
            'genreLitteraire',
            'audience',
            'secteur',
            'mediation',
            'contenu'
        ];
    }
}
