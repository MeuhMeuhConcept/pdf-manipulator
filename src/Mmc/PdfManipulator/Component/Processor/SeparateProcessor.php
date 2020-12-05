<?php

namespace Mmc\PdfManipulator\Component\Processor;

class SeparateProcessor extends AbstractCommandProcessor
{
    protected function getCommand($reference): string
    {
        $options = '-f '.$reference->getFrom();

        if ($reference->getTo() >= $reference->getFrom()) {
            $options .= ' -t '.$reference->getTo();
        }

        return sprintf(
            '%s %s %s %s',
            $this->binary,
            $options,
            $reference->getReference()->getFilename(),
            $reference->getFilename()
        );
    }
}
