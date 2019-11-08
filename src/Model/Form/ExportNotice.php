<?php
declare(strict_types=1);

namespace App\Model\Form;

use Symfony\Component\Validator\Constraints as Asset;

/**
 * Class ExportNotice
 * @package App\Model\Form
 */
final class ExportNotice
{
    /**
     * @var string
     * @Asset\NotBlank();
     * @Asset\Email();
     */
    private $receiver;
    /**
     * @var string
     */
    private $message;
    /**
     * @var bool
     */
    private $shortFormat = false;
    /**
     * @var string
     * @Asset\NotBlank();
     */
    private $formatType;
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
    private $authorities;

    /**
     * @var string
     */
    private $object;

    /**
     * @return string
     */
    public function getObject(): string
    {
        return $this->object;
    }

    /**
     * @return string
     */
    public function getNotices(): string
    {
        return (string) $this->notices;
    }

    /**
     * @param string|null $notices
     * @return ExportNotice
     */
    public function setNotices(string $notices=null): ExportNotice
    {
        $this->notices = $notices;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthorities(): string
    {
        return (string) $this->authorities;
    }

    /**
     * @param string|null $authorities
     * @return ExportNotice
     */
    public function setAuthorities(string $authorities=null): ExportNotice
    {
        $this->authorities = $authorities;
        return $this;
    }


    /**
     * @return null|string
     */
    public function getReceiver(): ?string
    {
        return $this->receiver;
    }

    /**
     * @param string $receiver
     * @return ExportNotice
     */
    public function setReceiver(string $receiver): ExportNotice
    {
        $this->receiver = $receiver;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return ExportNotice
     */
    public function setMessage(string $message): ExportNotice
    {
        $this->message = $message;
        return $this;
    }



    /**
     * @return null|string
     */
    public function getFormatType(): ?string
    {
        return $this->formatType;
    }

    /**
     * @param string $formatType
     * @return ExportNotice
     */
    public function setFormatType(string $formatType): ExportNotice
    {
        $this->formatType = $formatType;
        return $this;
    }

    /**
     * @return bool
     */
    public function isImage(): bool
    {
        return $this->image;
    }

    /**
     * @param bool $image
     * @return ExportNotice
     */
    public function setImage(bool $image): ExportNotice
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @param string $object
     * @return ExportNotice
     */
    public function setObject(string $object):ExportNotice
    {
        $this->object = $object;

        return $this;
    }

    /**
     * @return bool
     */
    public function isShortFormat(): bool
    {
        return $this->shortFormat;
    }

    /**
     * @param bool $shortFormat
     * @return $this
     */
    public function setShortFormat(bool $shortFormat): ExportNotice
    {
        $this->shortFormat = $shortFormat;

        return $this;
    }
}

