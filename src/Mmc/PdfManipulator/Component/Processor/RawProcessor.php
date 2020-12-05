<?php

namespace Mmc\PdfManipulator\Component\Processor;

use Mmc\PdfManipulator\Component\FileManager\FileManagerInterface;
use Mmc\PdfManipulator\Component\Reference\PdfRaw;

class RawProcessor extends AbstractProcessor
{
    public function __construct(
        FileManagerInterface $fileManager
    ) {
        $this->setFileManagerInterface($fileManager);
    }

    public function supports($request)
    {
        return $request instanceof PdfRaw;
    }

    protected function doProcess($request)
    {
        $this->fileManager->putFileContents($reference->getFilename(), $reference->getContent());

        $this->checkOutput($reference->getFilename());

        return true;
    }
}
