<?php

namespace Mmc\PdfManipulator\Component\Reference;

abstract class AbstractPdf implements Pdf
{
    protected $filename = '';

    protected $overwrite = false;

    protected $nbPages = 0;

    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @return self
     */
    public function setFilename(string $filename): Pdf
    {
        $this->filename = $filename;

        return $this;
    }

    public function getOverwrite(): bool
    {
        return $this->overwrite;
    }

    public function setOverwrite(bool $overwrite): Pdf
    {
        $this->overwrite = $overwrite;

        return $this;
    }

    public function getDependencies(): array
    {
        return [];
    }

    public function getNbPages(): int
    {
        return $this->nbPages;
    }

    public function setNbPages(int $nbPages): Pdf
    {
        $this->nbPages = $nbPages;

        return $this;
    }
}
