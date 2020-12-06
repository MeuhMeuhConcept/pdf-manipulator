<?php

namespace Mmc\PdfManipulator\Component\Reference;

class PdfMerge extends AbstractPdf
{
    protected $references;

    public function __construct()
    {
        $this->references = [];
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

    public function getDependencies(): array
    {
        return $this->references;
    }
}
