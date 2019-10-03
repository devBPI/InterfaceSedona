<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 01/10/19
 * Time: 11:27
 */

namespace App\Model\From;


interface PersonneInterface
{
    public function getFirstName(): ?string;
    public function getLastName(): ?string;
    public function getEmail(): ?string;
}

