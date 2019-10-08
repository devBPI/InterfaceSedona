<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 01/10/19
 * Time: 11:28
 */

namespace App\Model\From;
use Symfony\Component\Validator\Constraints as Asset;


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
     * @Asset\NotBlank();
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

