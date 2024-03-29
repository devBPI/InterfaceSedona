<?php


namespace App;

/**
 * Class WordsList
 * @package App
 */
final class WordsList
{
    public const ALL = 'all';
    public const FOR_KEYS = 'keys';
    public const THEME_DEFAULT = 'general';
    public const THEME_PRESS = 'actualites-revues';
    public const THEME_CINEMA = 'cinema';
    public const THEME_AUTOFORMATION = 'autoformation';

    public const SEARCH_TYPE_DEFAULT = 'simple';
    public const SEARCH_TYPE_ADVANCED = 'advanced';

    public static $words = [
        self::ALL => [
            self::GENERAL_WORD,
            self::TITLE_WORD,
            self::AUTHOR_WORD,
            self::SUBJECT_WORD,
            self::REALISATOR_WORD,
            self::PUBLICATION_DATE_WORD,
            self::ISBN_WORD,
            self::INDICE_COTE_WORD,
            self::THEME_WORD,
            self::EDITOR_WORD,
            self::COLLECTION_WORD
        ],
        self::FOR_KEYS => [
            self::GENERAL_WORD,
            self::TITLE_WORD,
            self::AUTHOR_WORD,
            self::SUBJECT_WORD,
            self::ISBN_WORD,
            self::INDICE_COTE_WORD,
            self::THEME_WORD,
            self::EDITOR_WORD,
            self::COLLECTION_WORD
        ],
        self::SEARCH_TYPE_DEFAULT => [
            self::THEME_DEFAULT => [
                self::GENERAL_WORD,
                self::TITLE_WORD,
                self::AUTHOR_WORD,
                self::SUBJECT_WORD,
                self::PUBLICATION_DATE_WORD,
                self::ISBN_WORD,
                self::INDICE_COTE_WORD,
                self::THEME_WORD
            ],
            self::THEME_CINEMA => [
                self::GENERAL_WORD,
                self::TITLE_WORD,
                self::AUTHOR_WORD,
                self::REALISATOR_WORD,
                self::SUBJECT_WORD,
                self::PUBLICATION_DATE_WORD,
                self::ISBN_WORD,
                self::INDICE_COTE_WORD
            ],
            self::THEME_AUTOFORMATION => [
                self::GENERAL_WORD,
                self::TITLE_WORD,
                self::AUTHOR_WORD,
                self::SUBJECT_WORD,
                self::THEME_WORD,
                self::EDITOR_WORD,
                self::PUBLICATION_DATE_WORD,
                self::ISBN_WORD,
                self::INDICE_COTE_WORD
            ]
        ],
        self::SEARCH_TYPE_ADVANCED => [
            self::THEME_DEFAULT => [
                self::GENERAL_WORD,
                self::TITLE_WORD,
                self::AUTHOR_WORD,
                self::SUBJECT_WORD,
                self::THEME_WORD,
                self::EDITOR_WORD,
                self::ISBN_WORD,
                self::INDICE_COTE_WORD,
                self::COLLECTION_WORD
            ],
            self::THEME_CINEMA => [
                self::GENERAL_WORD,
                self::TITLE_WORD,
                self::AUTHOR_WORD,
                self::REALISATOR_WORD,
                self::SUBJECT_WORD,
                self::THEME_WORD,
                self::EDITOR_WORD,
                self::ISBN_WORD,
                self::INDICE_COTE_WORD,
                self::COLLECTION_WORD
            ]
        ]
    ];

    public static $operators = [
        'and', 'or', 'notCriteria'
    ];

    const GENERAL_WORD = 'general';
    const TITLE_WORD = 'title';
    const AUTHOR_WORD = 'author';
    const REALISATOR_WORD = 'realisator';
    const SUBJECT_WORD = 'subject';
    const THEME_WORD = 'theme';
    const EDITOR_WORD = 'editor';
    const COLLECTION_WORD = 'collection';
    const PUBLICATION_DATE_WORD = 'publicationDate';
    const ISBN_WORD = 'isbnIssnNumcommercial';
    const INDICE_COTE_WORD = 'indiceCote';

    private function __construct()
    {
        // No-Op
    }

    /**
     * @param string $searchType
     * @param string $parcours
     * @return array
     */
    public static function getWords($searchType = self::SEARCH_TYPE_DEFAULT, $parcours = self::THEME_DEFAULT): array
    {
        $wordList = self::$words[$searchType];

        if (array_key_exists($parcours, $wordList)) {
            return $wordList[$parcours];
        }

        return $wordList[self::THEME_DEFAULT];
    }
}
