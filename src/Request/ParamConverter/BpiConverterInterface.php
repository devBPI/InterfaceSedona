<?php

namespace App\Request\ParamConverter;

/**
 * Interface BpiConverterInterface
 * @package App\Request\ParamConverter
 */
interface BpiConverterInterface
{
    public function getPermalink(): ?string;
}
