<?php

namespace Mmc\PdfManipulator\Component\Reference;

class PdfFile extends AbstractPdf
{
    public function __construct(
        string $filename
    ) {
        $this->filename = $filename;
    }
}
