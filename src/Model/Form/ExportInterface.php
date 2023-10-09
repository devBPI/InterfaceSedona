<?php
declare(strict_types=1);

namespace App\Model\Form;

interface ExportInterface
{

    public function isImage(): bool;

    public function isShortFormat(): bool;

    public function getFormatType(): ?string;

    public function hasNotices(): bool;

    public function getNotices(): string;

    public function setNotices(string $notices=null): self;

    public function getNoticesArray(): array;

    public function getAuthorities(): string;

    public function setAuthorities(string $authorities=null): self;

    public function hasAuthorities(): bool;

    public function getAuthoritiesArray(): array;

    public function getIndices(): ?string;

    public function setIndices(string $indices=null): self;

    public function hasIndices(): bool;

    public function getIndicesArray(): array;

    public function getTemplateType(): ?string;

    public function isDebug(): bool;

}