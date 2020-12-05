<?php

namespace Mmc\PdfManipulator\Component\Reference;

class PdfUnite extends AbstractPdf
{
    protected $references;

    public function __construct()
    {
        $this->reference = [];
    }

    public function addReference(Pdf $reference): self
    {
        $this->references[] = $reference;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getReferences(): array
    {
        return $this->references;
    }
}
