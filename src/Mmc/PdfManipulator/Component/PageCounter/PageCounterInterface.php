<?php

namespace Mmc\PdfManipulator\Component\PageCounter;

use Mmc\PdfManipulator\Component\Reference\Pdf;

interface PageCounterInterface
{
    public function countPage(Pdf $reference): int;
}
