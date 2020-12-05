<?php

namespace Mmc\PdfManipulator\Component\Processor;

class UniteProcessor extends AbstractCommandProcessor
{
    protected function getCommand($reference): string
    {
        $options = '-f '.$reference->getFrom();

        if ($reference->getTo() >= $reference->getFrom()) {
            $options .= ' -t '.$reference->getTo();
        }

        return sprintf(
            '%s %s %s',
            $this->binary,
            array_map($reference->getReferences(), function ($r) {
                return $r->getFilename().' ';
            }),
            $reference->getFilename()
        );
    }
}
