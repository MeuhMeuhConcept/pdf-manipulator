<?php

namespace Mmc\PdfManipulator\Component\Reference;

abstract class AbstractPdf implements Pdf
{
    protected $filename;

    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @return self
     */
    public function setFilename(string $filename)
    {
        $this->filename = $filename;

        return $this;
    }
}
