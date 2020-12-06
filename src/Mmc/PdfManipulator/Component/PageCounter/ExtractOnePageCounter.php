<?php

namespace Mmc\PdfManipulator\Component\PageCounter;

use Mmc\PdfManipulator\Component\Reference\PdfExtractOne;

class ExtractOnePageCounter extends AbstractPageCounter
{
    public function supports($request)
    {
        return $request instanceof PdfExtractOne;
    }

    protected function doProcess($request)
    {
        return 1;
    }
}
