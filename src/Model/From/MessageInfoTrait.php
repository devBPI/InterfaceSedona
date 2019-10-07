<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 30/09/19
 * Time: 16:53
 */

namespace App\Model\From;
use Symfony\Component\Validator\Constraints as Asset;


trait MessageInfoTrait
{
    /**
     * @var string
     * @Asset\NotBlank();
     */
    private $object;

    /**
     * @var string
     */
    private $message;


    /**
     * @return string
     */
    public function getObject(): ?string
    {
        return $this->object;
    }

    /**
     * @param string $object
     * @return ReportError
     */
    public function setObject(string $object): MessageInfoInterface
    {
        $this->object = $object;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return ReportError
     */
    public function setMessage(string $message): MessageInfoInterface
    {
        $this->message = $message;

        return $this;
    }

}

