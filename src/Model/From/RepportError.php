<?php


namespace App\Model\From;
use Symfony\Component\Validator\Constraints as Asset;


class RepportError
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
    public function getObject(): string
    {
        return $this->object;
    }

    /**
     * @param string $object
     * @return RepportError
     */
    public function setObject(string $object): RepportError
    {
        $this->object = $object;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return RepportError
     */
    public function setMessage(string $message): RepportError
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return RepportError
     */
    public function setLastName(string $lastName): RepportError
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return RepportError
     */
    public function setFirstName(string $firstName): RepportError
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return RepportError
     */
    public function setEmail(string $email): RepportError
    {
        $this->email = $email;
        return $this;
    }

}
