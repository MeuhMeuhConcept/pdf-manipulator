<?php

namespace Mmc\PdfManipulator\Component\Processor;

use Mmc\PdfManipulator\Component\Reference\PdfMerge;

class MergeProcessor extends AbstractCommandProcessor
{
    public function supports($request)
    {
        return $request instanceof PdfMerge;
    }

    protected function getCommand($reference): string
    {
        $filenames = array_map(
            function ($r) {
                return $r->getFilename().' ';
            },
            $reference->getReferences()
        );

        return sprintf(
            '%s %s %s',
            $this->binary,
            implode(' ', $filenames),
            $reference->getFilename()
        );
    }
}
