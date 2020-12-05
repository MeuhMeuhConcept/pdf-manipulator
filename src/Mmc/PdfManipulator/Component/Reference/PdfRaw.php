<?php

namespace Mmc\PdfManipulator\Component\Reference;

class PdfRaw extends AbstractPdf
{
    protected $content;

    public function __construct(
        string $content
    ) {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
