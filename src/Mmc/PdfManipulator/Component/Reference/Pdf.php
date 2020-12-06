<?php

namespace Mmc\PdfManipulator\Component\Reference;

interface Pdf
{
    public function getFilename(): string;

    public function setFilename(string $filename): Pdf;

    public function getOverwrite(): bool;

    public function setOverwrite(bool $overwrite): Pdf;

    public function getDependencies(): array;

    public function getNbPages(): int;

    public function setNbPages(int $nb): Pdf;
}
