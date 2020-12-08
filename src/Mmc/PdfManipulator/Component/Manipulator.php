<?php

namespace Mmc\PdfManipulator\Component;

use Mmc\PdfManipulator\Component\FileManager\FileManagerInterface;
use Mmc\Processor\Component\ChainProcessor;
use Mmc\Processor\Component\ResponseStatusCode;

class Manipulator extends ChainProcessor
{
    /**
     * @var FileManagerInterface
     */
    public $fileManager;

    public function __construct(
        FileManagerInterface $fileManager
    ) {
        $this->fileManager = $fileManager;
    }

    public function generate(Reference\Pdf $reference): self
    {
        return $this->_generate($reference, []);
    }

    protected function _generate(Reference\Pdf $reference, $parents): self
    {
        $filename = $reference->getFilename();

        if ($filename && $this->fileManager->fileExists($filename) && $this->fileManager->isFile($filename)) {
            return $this;
        }

        foreach ($reference->getDependencies() as $dependency) {
            if ($dependency == $reference) {
                throw new Exception\RuntimeException('Auto dependence');
            }
            if (in_array($dependency, $parents)) {
                throw new Exception\RuntimeException('Circular dependencies');
            }

            $this->_generate($dependency, array_merge($parents, [$reference]));
        }

        if (!$filename) {
            $filename = $this->fileManager->createTemporaryFile('pdf');
            $reference->setFilename($filename);
        }

        $this->fileManager->prepareOutput($filename, $reference->getOverwrite());

        $response = $this->process($reference);

        if (ResponseStatusCode::INTERNAL_ERROR == $response->getStatusCode()) {
            throw new Exception\RuntimeException($response->getOutput(), 0, $response->getOutput());
        }

        return $this;
    }

    public function getContent(Reference\Pdf $reference): string
    {
        $this->generate($reference);

        $result = $this->fileManager->getFileContents($reference->getFilename());

        return $result;
    }
}
