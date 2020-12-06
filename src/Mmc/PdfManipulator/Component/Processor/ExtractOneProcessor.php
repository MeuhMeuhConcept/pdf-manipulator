<?php

namespace Mmc\PdfManipulator\Component\Processor;

use Mmc\PdfManipulator\Component\Reference\PdfExtractOne;

class ExtractOneProcessor extends AbstractCommandProcessor
{
    public function supports($request)
    {
        return $request instanceof PdfExtractOne;
    }

    protected function getCommand($reference): string
    {
        return sprintf(
            '%s -f %d -l %d %s %s',
            $this->binary,
            $reference->getPage(),
            $reference->getPage(),
            $reference->getReference()->getFilename(),
            $reference->getFilename()
        );
    }
}
