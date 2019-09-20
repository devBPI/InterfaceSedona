<?php


namespace App;

/**
 * Class WordsList
 * @package App
 */
final class WordsList
{
    public const THEME_DEFAULT = 'general';
    public const THEME_PRESS = 'presse';
    public const THEME_CINEMA = 'cinema';
    public const THEME_AUTOFORMATION = 'autoformation';

    public static $words = [
        self::THEME_DEFAULT => [
            self::GENERAL_WORD,
            self::TITLE_WORD,
            self::AUTHOR_WORD,
            self::SUBJECT_WORD,
            self::PUBLICATION_DATE_WORD,
            self::ISBN_WORD,
            self::INDICE_COTE_WORD
        ],
        self::THEME_PRESS => [
            self::GENERAL_WORD,
            self::TITLE_WORD,
            self::AUTHOR_WORD,
            self::SUBJECT_WORD,
            self::PUBLICATION_DATE_WORD,
            self::ISBN_WORD,
            self::INDICE_COTE_WORD
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
            self::REALISATOR_WORD,
            self::SUBJECT_WORD,
            self::THEME_WORD,
            self::EDITOR_WORD,
            self::PUBLICATION_DATE_WORD,
            self::ISBN_WORD,
            self::INDICE_COTE_WORD
        ]
    ];
    public static $operators = [
        'and', 'or'/*, 'not' */
    ];

    const GENERAL_WORD = 'general';
    const TITLE_WORD = 'title';
    const AUTHOR_WORD = 'author';
    const REALISATOR_WORD = 'realisator';
    const SUBJECT_WORD = 'subject';
    const THEME_WORD = 'theme';
    const EDITOR_WORD = 'editor';
    const PUBLICATION_DATE_WORD = 'publication-date';
    const ISBN_WORD = 'isbn-issn-numcommercial';
    const INDICE_COTE_WORD = 'indice-cote';

    private function __construct()
    {
        // No-Op
    }
}
