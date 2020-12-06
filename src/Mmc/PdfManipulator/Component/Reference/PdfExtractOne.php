<?php

namespace Mmc\PdfManipulator\Component\Reference;

class PdfExtractOne extends AbstractPdf
{
    protected $reference;

    protected $page;

    public function __construct(
        Pdf $reference,
        int $page = 1
    ) {
        $this->reference = $reference;
        $this->page = $page;
    }

    public function getReference(): Pdf
    {
        return $this->reference;
    }

    public function setReference(Pdf $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getDependencies(): array
    {
        return [$this->reference];
    }
}
