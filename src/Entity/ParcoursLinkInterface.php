<?php

namespace App\Entity;

interface ParcoursLinkInterface
{
    public function getParcours(): string;
    public function getCode(): string;
}