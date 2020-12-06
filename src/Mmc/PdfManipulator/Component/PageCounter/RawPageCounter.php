<?php

namespace Mmc\PdfManipulator\Component\PageCounter;

use Mmc\PdfManipulator\Component\FileManager\FileManagerInterface;
use Mmc\PdfManipulator\Component\Info\InfoInterface;
use Mmc\PdfManipulator\Component\Reference\PdfRaw;

class RawPageCounter extends AbstractPageCounter
{
    protected $fileManager;
    protected $info;

    public function __construct(
        FileManagerInterface $fileManager,
        InfoInterface $info
    ) {
        $this->fileManager = $fileManager;
        $this->info = $info;
    }

    public function supports($request)
    {
        return $request instanceof PdfRaw;
    }

    protected function doProcess($request)
    {
        $filename = $this->fileManager->createTemporaryFile('pdf');

        $this->fileManager->putFileContents($filename, $request->getContent());

        return $this->info->get($filename)['Pages'];
    }
}
