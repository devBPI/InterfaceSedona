<?php
declare(strict_types=1);

namespace App\Model\Form;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ExportNotice
 * @package App\Model\Form
 */
class ExportNotice
{
    CONST PRINT_LONG = 'print-long';
    CONST FORMAT_PDF = 'pdf';
    CONST FORMAT_HTML = 'html';
    CONST FORMAT_EMAIL = 'eml';
    CONST FORMAT_TEXT = 'txt';

    /**
     * @var bool
     */
    private $shortFormat = false;

    /**
     * @var string
     * @Assert\NotBlank(message="formattype.empty");
     */
    private $formatType = self::FORMAT_PDF;

    /**
     * @var bool
     */
    private $image = false;

    /**
     * @var string
     */
    private $notices;

    /**
     * @var string
     */
    private $indices;
    /**
     * @var string
     */
    private $authorities;

    /**
     * @var bool
     */
    private $forceDownload = true;

    public function getNotices(): string
    {
        return (string) $this->notices;
    }

    public function hasNotices(): bool
    {
        return !empty($this->notices);
    }

    public function getNoticesArray(): array
    {
        if (!$this->hasAuthorities()) {
            return [];
        }
        return array_unique(\json_decode($this->notices, false));
    }

    public function setNotices(string $notices=null): self
    {
        $this->notices = $notices;
        return $this;
    }

    public function getAuthorities(): string
    {
        return (string) $this->authorities;
    }

    public function hasAuthorities(): bool
    {
        return !empty($this->authorities);
    }

    public function getAuthoritiesArray(): array
    {
        if (!$this->hasAuthorities()) {
            return [];
        }
        return array_unique(\json_decode($this->authorities, false));
    }

    public function setAuthorities(string $authorities=null): self
    {
        $this->authorities = $authorities;
        return $this;
    }

    public function getFormatType(): ?string
    {
        return $this->formatType;
    }

    public function getTemplateType(): ?string
    {
        if ($this->formatType === self::FORMAT_HTML || $this->isFormatEmail()) {
            return self::FORMAT_PDF;
        }

        return $this->formatType;
    }

    public function isFormatEmail() :bool
    {
        return $this->formatType === self::FORMAT_EMAIL;
    }

    public function setFormatType(string $formatType): self
    {
        $this->formatType = $formatType;
        return $this;
    }

    public function isImage(): bool
    {
        return $this->image;
    }

    public function setImage(bool $image): self
    {
        $this->image = $image;
        return $this;
    }

    public function isShortFormat(): bool
    {
        return $this->shortFormat;
    }


    public function setShortFormat(bool $shortFormat): self
    {
        $this->shortFormat = $shortFormat;

        return $this;
    }

    public function getIndices(): ?string
    {
        return $this->indices;
    }

    public function hasIndices(): bool
    {
        return !empty($this->indices);
    }

    public function getIndicesArray(): array
    {
        if (!$this->hasIndices()) {
            return [];
        }
        return array_unique(\json_decode($this->indices, false));
    }

    public function setIndices(string $indices=null): self
    {
        $this->indices = $indices;

        return $this;
    }

    public function isForceDownload(): bool
    {
        return $this->forceDownload;
    }

    public function setForceDownload(bool $forceDownload): self
    {
        $this->forceDownload = $forceDownload;
        return $this;
    }


    static function createFromRequest(Request $request, string $format = self::FORMAT_PDF) :self
    {
        return (new ExportNotice())
            ->setShortFormat($request->get('print-type', self::PRINT_LONG) !== self::PRINT_LONG)
            ->setImage($request->get('print-image', null) === 'print-image')
            ->setFormatType($request->get('format-type',$format))
            ->setForceDownload($request->get('force-download', "on") !== "off");
    }
}

