<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class NoticeAvailabilityRequest
 * @package App\Entity
 *
 * @ORM\Entity()
 * @UniqueEntity(fields={"notice_configuration_id", "notice_source_id", "requester"})
 */
class NoticeAvailabilityRequest
{
    /**
     * @param string $requester
     */
    public function setRequester(string $requester)
    {
        $this->requester = $requester;
    }
    /**
     * @param \DateTime $modification_date
     * @return NoticeAvailabilityRequest
     */
    public function setModificationDate(\DateTime $modification_date): NoticeAvailabilityRequest
    {
        $this->modification_date = $modification_date;

        return $this;
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(type="bigint")
     * @ORM\SequenceGenerator(sequenceName="notice_avail_rqst_id_seq")
     */
    private $id;

    /**
     * @ORM\Column(type="bigint", nullable=false)
     * @var integer
     */
    private $notice_configuration_id;

    /**
     * @ORM\Column(type="string", length=1024, nullable=false)
     * @var integer
     */
    private $notice_source_id;

    /**
     * @ORM\Column(type="datetime", options={"default" = "CURRENT_TIMESTAMP"})
     * @var \DateTime
     */
    private $request_date;

    /**
     * @ORM\Column(type="datetime", options={"default" = "CURRENT_TIMESTAMP"})
     * @var \DateTime
     */
    private $modification_date;

    /**
     * @ORM\Column(type="string", length=1024, nullable=true)
     * @var string
     */
    private $requester;

    /**
     * @ORM\Column(type="string", length=1024, nullable=true)
     * @var string
     */
    private $notification_email;

    /**
     * @ORM\Column(type="string", length=1024, nullable=true)
     * @var string
     */
    private $comment;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getNoticeConfigurationId(): int
    {
        return $this->notice_configuration_id;
    }

    /**
     * @return int
     */
    public function getNoticeSourceId(): int
    {
        return $this->notice_source_id;
    }

    /**
     * @return \DateTime
     */
    public function getRequestDate(): \DateTime
    {
        return $this->request_date;
    }

    /**
     * @return \DateTime
     */
    public function getModificationDate(): \DateTime
    {
        return $this->modification_date;
    }

    /**
     * @return string
     */
    public function getRequester(): string
    {
        return $this->requester;
    }

    /**
     * @return string
     */
    public function getNotificationEmail(): string
    {
        return $this->notification_email;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @param int $notice_source_id
     * @return NoticeAvailabilityRequest
     */
    public function setNoticeSourceId(int $notice_source_id): NoticeAvailabilityRequest
    {
        $this->notice_source_id = $notice_source_id;
        return $this;
    }

    /**
     * @param \DateTime $request_date
     * @return NoticeAvailabilityRequest
     */
    public function setRequestDate(\DateTime $request_date): NoticeAvailabilityRequest
    {
        $this->request_date = $request_date;
        return $this;
    }

    /**
     * @param string $notification_email
     * @return NoticeAvailabilityRequest
     */
    public function setNotificationEmail(string $notification_email): NoticeAvailabilityRequest
    {
        $this->notification_email = $notification_email;
        return $this;
    }
    /**
     * @param int $notice_configuration_id
     * @return NoticeAvailabilityRequest
     */
    public function setNoticeConfigurationId(int $notice_configuration_id): NoticeAvailabilityRequest
    {
        $this->notice_configuration_id = $notice_configuration_id;
        return $this;
    }
}

