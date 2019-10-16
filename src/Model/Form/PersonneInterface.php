<?php

namespace App\Model\Form;

/**
 * Interface PersonneInterface
 * @package App\Model\Form
 */
interface PersonneInterface
{
    public function getFirstName(): ?string;
    public function getLastName(): ?string;
    public function getEmail(): ?string;
}

