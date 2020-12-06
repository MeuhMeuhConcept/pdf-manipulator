<?php

namespace Mmc\PdfManipulator\Component\PageCounter;

use Mmc\PdfManipulator\Component\Exception;
use Mmc\PdfManipulator\Component\Reference\Pdf;
use Mmc\Processor\Component\ChainProcessor;
use Mmc\Processor\Component\ResponseStatusCode;

class ChainPageCounter extends ChainProcessor implements PageCounterInterface
{
    public function countPage(Pdf $reference): int
    {
        return $this->_countPage($reference, []);
    }

    protected function _countPage(Pdf $reference, $parents): int
    {
        if ($reference->getNbPages() > 0) {
            return $reference->getNbPages();
        }

        foreach ($reference->getDependencies() as $dependency) {
            if ($dependency == $reference) {
                throw new Exception\RuntimeException('Auto dependence');
            }
            if (in_array($dependency, $parents)) {
                throw new Exception\RuntimeException('Circular dependencies');
            }

            $this->_countPage($dependency, array_merge($parents, [$reference]));
        }

        $response = $this->process($reference);

        if (ResponseStatusCode::INTERNAL_ERROR == $response->getStatusCode()) {
            throw new Exception\RuntimeException($response->getOutput());
        }

        $reference->setNbPages($response->getOutput());

        return $response->getOutput();
    }
}
