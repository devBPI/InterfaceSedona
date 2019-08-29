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
}

