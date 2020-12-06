<?php

namespace Mmc\PdfManipulator\Component\PageCounter;

use Mmc\PdfManipulator\Component\Info\InfoInterface;
use Mmc\PdfManipulator\Component\Reference\PdfFile;

class FilePageCounter extends AbstractPageCounter
{
    protected $info;

    public function __construct(
        InfoInterface $info
    ) {
        $this->info = $info;
    }

    public function supports($request)
    {
        return $request instanceof PdfFile;
    }

    protected function doProcess($request)
    {
        return $this->info->get($request->getFilename())['Pages'];
    }
}
