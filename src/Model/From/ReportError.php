<?php


namespace App\Model\From;
use Symfony\Component\Validator\Constraints as Asset;


class ReportError
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
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     * @Asset\Email();
     */
    private $email;

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
    public function setObject(string $object): ReportError
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
    public function setMessage(string $message): ReportError
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return ReportError
     */
    public function setLastName(string $lastName): ReportError
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return ReportError
     */
    public function setFirstName(string $firstName): ReportError
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return ReportError
     */
    public function setEmail(string $email): ReportError
    {
        $this->email = $email;
        return $this;
    }

}
