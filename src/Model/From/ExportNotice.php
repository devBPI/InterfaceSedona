<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 01/10/19
 * Time: 17:58
 */
namespace App\Model\From;
use Symfony\Component\Validator\Constraints as Asset;

class ExportNotice
{
    /**
     * @var string
     * @Asset\Email();
     */
    private $reciever;
    /**
     * @var string
     */
    private $message;
    /**
     * @var bool
     * @Asset\NotBlank();
     */
    private $shortFormat;
    /**
     * @var string
     * @Asset\NotBlank();
     */
    private $formatType;
    /**
     * @var bool
     */
    private $image;

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
     * @return $thi
     */
    public function setNotices(string $notices=null)
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
     * @return $this
     */
    public function setAuthorities(string $authorities=null)
    {
        $this->authorities = $authorities;
        return $this;
    }


    /**
     * @return null|string
     */
    public function getReciever(): ?string
    {
        return $this->reciever;
    }

    /**
     * @param string $reciever
     * @return ExportNotice
     */
    public function setReciever(string $reciever): ExportNotice
    {
        $this->reciever = $reciever;
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
     * @return bool|null
     */
    public function isImage(): ?bool
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
     * @return bool|null
     */
    public function isShortFormat(): ?bool
    {
        return $this->shortFormat;

    }

    /**
     * @param bool $shortFormat
     * @return $this
     */
    public function setShortFormat(bool $shortFormat)
    {
        $this->shortFormat = $shortFormat;

        return $this;
    }
}

