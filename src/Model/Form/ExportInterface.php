<?php
declare(strict_types=1);

namespace App\Model\Form;

interface ExportInterface
{

    public function isImage(): bool;

    public function isShortFormat(): bool;

    public function getFormatType(): ?string;

    public function getIndices(): ?string;

    public function hasNotices(): bool;

    public function getNoticesArray(): array;

    public function hasAuthorities(): bool;

    public function getAuthoritiesArray(): array;

    public function hasIndices(): bool;

    public function getIndicesArray(): array;

    public function getTemplateType(): ?string;

}