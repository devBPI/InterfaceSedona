<?php

namespace App\Model\Form;

use Symfony\Component\Validator\Constraints as Asset;

/**
 * Trait PersonneTrait
 * @package App\Model\Form
 */
trait PersonneTrait
{
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
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return ReportError
     */
    public function setEmail(string $email): PersonneInterface
    {
        $this->email = $email;

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
     * @return PersonneInterface
     */
    public function setLastName(string $lastName): PersonneInterface
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
     * @return PersonneInterface
     */
    public function setFirstName(string $firstName): PersonneInterface
    {
        $this->firstName = $firstName;
        return $this;
    }
}

