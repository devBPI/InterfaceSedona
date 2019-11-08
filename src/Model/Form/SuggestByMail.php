<?php

namespace App\Model\Form;

use Symfony\Component\Validator\Constraints as Asset;

/**
 * Class SuggestByMail
 * @package App\Model\Form
 */
class SuggestByMail implements PersonneInterface
{
    use PersonneTrait;
    const DOCUMENT_TYPE = [
        'Livre' => 'Livre',
        'Livre numérique' => 'Livre numérique',
        'Musique' => 'Musique',
        'Vidéo' => 'Vidéo',
        'BD' => 'BD',
        'Revue, journal' => 'Revue, journal',
        'Revue numérique' => 'Revue numérique',
        'Site et base' => 'Site et base',
        'Livre audio' => 'Livre audio',
        'Débat et enregistrement' => 'Débat et enregistrement',
        'Carte' => 'Carte',
        'Partition et méthode' => 'Partition et méthode',
        'Formation' => 'Formation'
    ];
    /**
     * @var string
     */
    private $author;

    /**
     * @Asset\NotBlank(message="text.empty");
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $editor;

    /**
     * @var string
     */
    private $documentType;

    /**
     * @return string
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * @param string $author
     * @return SuggestByMail
     */
    public function setAuthor(string $author): SuggestByMail
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return string
     */
    public function getEditor(): ?string
    {
        return $this->editor;
    }

    /**
     * @param string $editor
     * @return SuggestByMail
     */
    public function setEditor(string $editor): SuggestByMail
    {
        $this->editor = $editor;
        return $this;
    }

    /**
     * @return string
     */
    public function getDocumentType(): ?string
    {
        return $this->documentType;
    }

    /**
     * @param string $documentType
     * @return SuggestByMail
     */
    public function setDocumentType(string $documentType): SuggestByMail
    {
        $this->documentType = $documentType;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return SuggestByMail
     */
    public function setTitle(string $title): SuggestByMail
    {
        $this->title = $title;
        return $this;
    }
}
