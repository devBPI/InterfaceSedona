<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(type="bigint")
     * @ORM\SequenceGenerator(sequenceName="notice_avail_rqst_id_seq")
     */
    private $id;

    /**
     * @ORM\Column(type="bigint", nullable=true)
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
     * @Assert\NotBlank(message="email.empty")
     * @Assert\Email
     */
    private $notification_email;

    /**
     * @ORM\Column(type="string", length=1024, nullable=true)
     * @var string
     */
    private $comment;

    /**
     * NoticeAvailabilityRequest constructor.
     * @param int $sourceId
     * @param int $configurationId
     */
    public function __construct(int $sourceId, int $configurationId = null)
    {
        $this->notice_source_id = $sourceId;
        $this->notice_configuration_id = $configurationId;
        $this->request_date = new \DateTime('now');
        $this->modification_date = new \DateTime('now');
    }

    /**
     * @return string
     */
    public function getNotificationEmail(): ?string
    {
        return $this->notification_email;
    }

    /**
     * @param string $notification_email
     * @return self
     */
    public function setNotificationEmail($notification_email): self
    {
        $this->notification_email = $notification_email;

        return $this;
    }

    /**
     * @param string $requester
     */
    public function setRequester(string $requester)
    {
        $this->requester = $requester;
    }

}

