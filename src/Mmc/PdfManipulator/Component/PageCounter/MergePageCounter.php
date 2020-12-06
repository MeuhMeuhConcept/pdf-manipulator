<?php

namespace Mmc\PdfManipulator\Component\PageCounter;

use Mmc\PdfManipulator\Component\Exception;
use Mmc\PdfManipulator\Component\Reference\PdfMerge;

class MergePageCounter extends AbstractPageCounter
{
    public function supports($request)
    {
        return $request instanceof PdfMerge;
    }

    protected function doProcess($request)
    {
        $nb = 0;

        foreach ($request->getReferences() as $reference) {
            if (0 == $reference->getNbPages()) {
                throw new Exception\RuntimeException('One dependency pdf has no page');
            }

            $nb += $reference->getNbPages();
        }

        return $nb;
    }
}
