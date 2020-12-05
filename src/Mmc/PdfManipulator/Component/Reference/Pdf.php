<?php

namespace Mmc\PdfManipulator\Component\Reference;

interface Pdf
{
    public function getFilename(): string;

    public function setFilename(string $filename): self;
}
