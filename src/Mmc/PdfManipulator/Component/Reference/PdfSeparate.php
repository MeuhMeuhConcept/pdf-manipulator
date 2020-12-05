<?php

namespace Mmc\PdfManipulator\Component\Reference;

class PdfSeparate extends AbstractPdf
{
    protected $reference;

    protected $from;

    protected $to;

    public function __construct(
        Pdf $reference,
        int $from = 1,
        int $to = 0
    ) {
        $this->reference = $reference;
        $this->from = $from;
        $this->to = $to;
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

    public function getFrom(): int
    {
        return $this->from;
    }

    public function setFrom(int $from): self
    {
        $this->from = $from;

        return $this;
    }

    public function getTo(): int
    {
        return $this->to;
    }

    public function setTo(int $to): self
    {
        $this->to = $to;

        return $this;
    }
}
